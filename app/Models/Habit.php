<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tracking()
    {
        return $this->hasMany(HabitTracking::class);
    }

    public function streak()
    {
        return $this->hasOne(Streak::class);
    }

    public function isCompletedOn($date)
    {
        return $this->tracking()->where('completed_date', $date)->exists();
    }

    public function getCompletedDatesForMonth($year, $month)
    {
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end = sprintf('%04d-%02d-31', $year, $month);

        return $this->tracking()
            ->whereBetween('completed_date', [$start, $end])
            ->pluck('completed_date')
            ->map(fn ($date) => (string) $date);
    }

    public function calculateStreak()
    {
        $streak = $this->streak ?: Streak::create([
            'habit_id' => $this->id,
            'current_count' => 0,
            'longest_count' => 0,
        ]);

        $streak->updateFromLogs();

        return $streak;
    }
}
