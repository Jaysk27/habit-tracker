<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Streak extends Model
{
    protected $fillable = ['habit_id', 'current_count', 'longest_count', 'last_completion_date'];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function updateFromLogs()
    {
        $tracking = $this->habit->tracking()->orderBy('completed_date', 'desc')->get();

        if ($this->habit->type === 'bad') {
            $this->updateFromLogsForBadHabit($tracking);

            return;
        }

        if ($tracking->isEmpty()) {
            $this->current_count = 0;
            $this->save();

            return;
        }

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $lastCompletion = $tracking->first()->completed_date;

        if ($lastCompletion === $today || $lastCompletion === $yesterday) {
            $this->current_count = 1;
            $prevDate = $lastCompletion;

            foreach ($tracking as $log) {
                $expectedDate = date('Y-m-d', strtotime($prevDate.' -1 day'));
                if ($log->completed_date === $expectedDate) {
                    $this->current_count++;
                    $prevDate = $log->completed_date;
                } else {
                    break;
                }
            }
        } else {
            $this->current_count = 0;
        }

        $this->last_completion_date = $lastCompletion;

        $this->longest_count = $this->calculateLongestStreak($tracking);

        $this->save();
    }

    public function calculateLongestStreak($tracking)
    {
        if ($tracking->isEmpty()) {
            return 0;
        }

        $dates = $tracking->pluck('completed_date')->toArray();
        sort($dates);

        $longestStreak = 1;
        $currentStreak = 1;
        $prevDate = $dates[0];

        for ($i = 1; $i < count($dates); $i++) {
            $expectedDate = date('Y-m-d', strtotime($prevDate.' +1 day'));
            if ($dates[$i] === $expectedDate) {
                $currentStreak++;
                if ($currentStreak > $longestStreak) {
                    $longestStreak = $currentStreak;
                }
            } else {
                $currentStreak = 1;
            }
            $prevDate = $dates[$i];
        }

        return max($longestStreak, $this->current_count);
    }

    private function updateFromLogsForBadHabit($tracking)
    {
        $completedDates = $tracking->pluck('completed_date')->toArray();

        $today = now()->toDateString();

        if (in_array($today, $completedDates)) {
            $this->current_count = 0;
            $this->longest_count = $this->calculateLongestStreakForBadHabit($completedDates);
            $this->save();

            return;
        }

        $yesterday = now()->subDay()->toDateString();

        if (in_array($yesterday, $completedDates)) {
            $this->current_count = 1;
        } else {
            $this->current_count = 0;
        }

        $streak = $this->current_count;
        $checkDate = $yesterday;

        for ($i = 1; $i < 365; $i++) {
            $checkDate = date('Y-m-d', strtotime($checkDate.' -1 day'));
            if (! in_array($checkDate, $completedDates)) {
                $streak++;
            } else {
                break;
            }
        }

        $this->current_count = $streak;
        $this->longest_count = $this->calculateLongestStreakForBadHabit($completedDates);

        $this->save();
    }

    public function calculateLongestStreakForBadHabit($completedDates)
    {
        if (empty($completedDates)) {
            return 0;
        }

        $dates = $completedDates;
        sort($dates);

        $longestStreak = 0;
        $currentStreak = 0;
        $prevDate = null;

        foreach ($dates as $date) {
            if ($prevDate === null) {
                $prevDate = $date;
                continue;
            }

            $expectedDate = date('Y-m-d', strtotime($prevDate.' +1 day'));
            if ($date === $expectedDate) {
                $currentStreak = 0;
            } else {
                $daysAvoided = (strtotime($date) - strtotime($prevDate)) / 86400;
                $currentStreak = max($currentStreak, $daysAvoided);
                if ($currentStreak > $longestStreak) {
                    $longestStreak = $currentStreak;
                }
            }
            $prevDate = $date;
        }

        return max($longestStreak, $this->current_count);
    }
}
