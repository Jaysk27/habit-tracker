<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShareLink;
use App\Models\Habit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ShareController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'habit_id' => 'nullable|exists:habits,id',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'completion_rate' => 'nullable|numeric|min:0|max:100',
            'current_streak' => 'nullable|integer',
            'total_completions' => 'nullable|integer',
        ]);

        $habit = null;
        if ($request->habit_id) {
            $habit = Habit::where('id', $request->habit_id)
                ->where('user_id', Auth::id())
                ->first();
        }

        $shareLink = ShareLink::create([
            'short_code' => ShareLink::generateShortCode(),
            'user_id' => Auth::id(),
            'habit_id' => $request->habit_id,
            'year' => $request->year,
            'month' => $request->month,
            'completion_rate' => $request->completion_rate,
            'current_streak' => $request->current_streak,
            'total_completions' => $request->total_completions,
            'expires_at' => now()->addDays(30),
        ]);

        $this->generateOgImage($shareLink);

        return response()->json([
            'success' => true,
            'short_code' => $shareLink->short_code,
            'url' => $shareLink->share_url,
        ]);
    }

    protected function generateOgImage(ShareLink $shareLink): void
    {
        $habit = $shareLink->habit;
        $habitName = $habit ? $habit->name : 'All Habits';
        $type = $habit ? ($habit->type === 'bad' ? 'Avoid' : 'Build') : 'Overall';
        
        $completionRate = $shareLink->completion_rate ?? 0;
        $currentStreak = $shareLink->current_streak ?? 0;
        $totalCompletions = $shareLink->total_completions ?? 0;
        $year = $shareLink->year;
        $month = $shareLink->month;
        
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            width: 1200px;
            height: 630px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }
        .card {
            background: white;
            border-radius: 32px;
            padding: 48px;
            width: 100%;
            max-width: 1000px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 32px;
        }
        .logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo span {
            color: white;
            font-size: 28px;
            font-weight: bold;
        }
        .brand {
            font-size: 20px;
            color: #6366f1;
            font-weight: 600;
        }
        .title {
            font-size: 48px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 24px;
            color: #6b7280;
            margin-bottom: 40px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }
        .stat {
            background: #f9fafb;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
        }
        .stat-value {
            font-size: 40px;
            font-weight: 800;
            color: #6366f1;
        }
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }
        .badge {
            display: inline-block;
            padding: 8px 16px;
            background: #e0e7ff;
            color: #6366f1;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 600;
        }
        .date {
            color: #9ca3af;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="logo"><span>H</span></div>
            <span class="brand">Habit Tracker</span>
        </div>
        <h1 class="title">{$habitName}</h1>
        <p class="subtitle">Monthly Progress Summary</p>
        <div class="stats">
            <div class="stat">
                <div class="stat-value">{$completionRate}%</div>
                <div class="stat-label">Completion Rate</div>
            </div>
            <div class="stat">
                <div class="stat-value">{$currentStreak}</div>
                <div class="stat-label">Current Streak</div>
            </div>
            <div class="stat">
                <div class="stat-value">{$totalCompletions}</div>
                <div class="stat-label">Total Completions</div>
            </div>
        </div>
        <div class="footer">
            <span class="badge">{$type}</span>
            <span class="date">{$year} - {$month}</span>
        </div>
    </div>
</body>
</html>
HTML;

        $directory = public_path('share-images');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $htmlPath = $directory . '/' . $shareLink->short_code . '.html';
        file_put_contents($htmlPath, $html);

        $shareLink->og_image_path = 'share-images/' . $shareLink->short_code . '.html';
        $shareLink->save();
    }

    public function show($shortCode)
    {
        $shareLink = ShareLink::where('short_code', $shortCode)->firstOrFail();
        
        $shareLink->increment('views');
        
        $habit = null;
        if ($shareLink->habit_id) {
            try {
                $habit = \App\Models\Habit::find($shareLink->habit_id);
            } catch (\Exception $e) {
                $habit = null;
            }
        }
        
        $monthName = \Carbon\Carbon::createFromDate($shareLink->year, $shareLink->month, 1)->format('F Y');
        
        return view('share.show', compact('shareLink', 'habit', 'monthName'));
    }
}