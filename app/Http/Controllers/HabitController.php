<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $search = $request->input('search');
        $filterType = $request->input('type', 'all');

        $habits = $user->habits()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($filterType !== 'all', fn ($q) => $q->where('type', $filterType))
            ->with('tracking', 'streak')
            ->get();

        foreach ($habits as $habit) {
            $habit->calculateStreak();
        }

        $calendarData = [];
        foreach ($habits as $habit) {
            $calendarData[$habit->id] = $habit->getCompletedDatesForMonth($year, $month)->toArray();
        }

        $progressSummary = $user->getProgressSummary();

        return view('habits.index', compact('habits', 'calendarData', 'progressSummary', 'year', 'month', 'search', 'filterType'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'type' => 'required|in:good,bad']);

        $habit = Auth::user()->habits()->create($request->all());

        $habit->streak()->create([
            'current_count' => 0,
            'longest_count' => 0,
        ]);

        return redirect()->route('habits.index');
    }

    public function update(Request $request, Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['name' => 'required']);
        $habit->update($request->only(['name', 'description']));

        return redirect()->route('habits.index');
    }

    public function destroy(Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $habit->delete();

        return redirect()->route('habits.index');
    }

    public function toggle(Habit $habit, Request $request)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $date = $request->input('date', now()->toDateString());

        $existing = $habit->tracking()->where('completed_date', $date)->first();

        if ($existing) {
            $existing->delete();
        } else {
            $habit->tracking()->create(['completed_date' => $date]);
        }

        $habit->calculateStreak();

        return redirect()->route('habits.index', [
            'year' => $request->input('year', now()->year),
            'month' => $request->input('month', now()->month),
        ]);
    }

    public function summary(Request $request)
    {
        $user = Auth::user();
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $habits = $user->habits()->with('tracking', 'streak')->get();

        foreach ($habits as $habit) {
            $habit->calculateStreak();
        }

        // Calculate monthly statistics for each habit
        $habitStats = [];
        foreach ($habits as $habit) {
            $completedDates = $habit->getCompletedDatesForMonth($year, $month);
            $daysInMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;
            
            // For bad habits, completion rate is days AVOIDED (not completed)
            // For good habits, completion rate is days COMPLETED
            if ($habit->type === 'bad') {
                $daysAvoided = $daysInMonth - count($completedDates);
                $completionRate = $daysInMonth > 0 ? round(($daysAvoided / $daysInMonth) * 100, 1) : 0;
            } else {
                $completionRate = $daysInMonth > 0 ? round((count($completedDates) / $daysInMonth) * 100, 1) : 0;
            }

            $habitStats[] = [
                'habit' => $habit,
                'completed_count' => count($completedDates),
                'days_in_month' => $daysInMonth,
                'completion_rate' => $completionRate,
                'current_streak' => $habit->streak?->current_count ?? 0,
                'longest_streak' => $habit->streak?->longest_count ?? 0,
            ];
        }

        // Sort by completion rate descending
        usort($habitStats, fn ($a, $b) => $b['completion_rate'] <=> $a['completion_rate']);

        // Calculate overall statistics
        $totalHabits = count($habits);
        $totalCompletions = array_sum(array_column($habitStats, 'completed_count'));
        $totalPossibleDays = $totalHabits * \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $overallCompletion = $totalPossibleDays > 0 ? round(($totalCompletions / $totalPossibleDays) * 100, 1) : 0;

        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y');

        return view('habits.summary', compact(
            'habitStats',
            'totalHabits',
            'totalCompletions',
            'overallCompletion',
            'year',
            'month',
            'monthName'
        ));
    }
}
