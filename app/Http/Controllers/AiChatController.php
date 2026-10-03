<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Habit;
use Illuminate\Support\Facades\Auth;

class AiChatController extends Controller
{
    public function index()
    {
        $apiKey = config('services.openrouter.key');
        $habits = Auth::user()->habits()->get();

        return view('ai-chat', compact('habits'))->with('hasApiKey', !empty($apiKey));
    }

    public function chat(Request $request)
    {
        $apiKey = config('services.openrouter.key');

        if (empty($apiKey)) {
            return response()->json(['error' => 'OpenRouter API key not configured'], 400);
        }

        $request->validate([
            'message' => 'required|string',
            'context' => 'nullable|string',
        ]);

        $user = Auth::user();
        $habits = $user->habits()->get();

        $habitContext = "Your current habits:\n";
        foreach ($habits as $habit) {
            $type = $habit->type === 'bad' ? '(Bad habit - avoid)' : '(Good habit - build)';
            $streak = $habit->streak?->current_count ?? 0;
            $habitContext .= "- {$habit->name} {$type}. Current streak: {$streak} days.\n";
        }

        $systemPrompt = "You are a helpful habit coaching assistant. You help users track and improve their habits. " .
            "Be encouraging, practical, and provide actionable advice. " .
            "Keep responses concise and conversational. " .
            "Here is the user's habit information:\n\n" . $habitContext;

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $request->input('context') . "\n\n" . $request->input('message')]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openrouter/auto',
                'messages' => $messages,
                'temperature' => 0.7,
            ]);

            $data = $response->json();

            if (isset($data['choices'][0]['message']['content'])) {
                return response()->json([
                    'response' => $data['choices'][0]['message']['content']
                ]);
            }

            return response()->json(['error' => 'Invalid response from AI'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to connect to AI: ' . $e->getMessage()], 500);
        }
    }

    public function getContext(Request $request)
    {
        $type = $request->input('type');
        $habitId = $request->input('habit_id');

        $user = Auth::user();

        switch ($type) {
            case 'today':
                $habits = $user->habits()->get();
                $completedToday = 0;
                $totalHabits = $habits->count();

                foreach ($habits as $habit) {
                    $completions = $habit->completions()
                        ->whereDate('completed_at', today())
                        ->count();
                    if ($completions > 0) $completedToday++;
                }

                return response()->json([
                    'context' => "The user is checking in for TODAY. They've completed {$completedToday} out of {$totalHabits} habits today. Respond with an encouraging message and ask if they need help with any specific habits."
                ]);

            case 'progress':
                $habit = null;
                if ($habitId) {
                    $habit = $user->habits()->find($habitId);
                }

                $habits = $user->habits()->get();
                $totalCompleted = 0;
                $totalPossible = 0;

                foreach ($habits as $h) {
                    $completed = $h->completions()->count();
                    $totalCompleted += $completed;
                    $totalPossible += 30;
                }

                $rate = $totalPossible > 0 ? round(($totalCompleted / $totalPossible) * 100) : 0;

                $context = "The user wants to discuss their overall progress. Overall completion rate: {$rate}% ({$totalCompleted} total completions).";
                if ($habit) {
                    $streak = $habit->streak?->current_count ?? 0;
                    $longest = $habit->streak?->longest_count ?? 0;
                    $habitType = $habit->type === 'bad' ? 'bad habit to avoid' : 'good habit to build';
                    $context .= " Specifically about habit: {$habit->name} ({$habitType}), current streak: {$streak} days, longest streak: {$longest} days.";
                }

                return response()->json(['context' => $context]);

            case 'struggling':
                $habit = null;
                if ($habitId) {
                    $habit = $user->habits()->find($habitId);
                }

                if ($habit) {
                    return response()->json([
                        'context' => "The user is STRUGGLING with the habit: {$habit->name}. Provide motivation, break down the habit into smaller steps, and suggest strategies to make it easier. Be empathetic and practical."
                    ]);
                }

                return response()->json([
                    'context' => "The user is struggling with their habits in general. Ask which habit they're having trouble with and provide general motivation and practical advice."
                ]);

            case 'suggestions':
                return response()->json([
                    'context' => "The user is asking for suggestions to improve their habits. Provide 3-5 actionable suggestions tailored to their specific habits. Focus on practical strategies like habit stacking, environment design, or starting small."
                ]);

            default:
                return response()->json(['context' => '']);
        }
    }
}
