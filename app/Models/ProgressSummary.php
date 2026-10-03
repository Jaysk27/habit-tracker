<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressSummary extends Model
{
    protected $fillable = ['user_id', 'total_habits', 'completed_today', 'completion_rate', 'last_calculated'];

    protected $casts = [
        'last_calculated' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recalculate()
    {
        $user = $this->user;
        $habits = $user->habits;

        $this->total_habits = $habits->count();

        $today = now()->toDateString();
        $this->completed_today = $habits->filter(function ($habit) use ($today) {
            return $habit->isCompletedOn($today);
        })->count();

        if ($this->total_habits > 0) {
            $this->completion_rate = round(($this->completed_today / $this->total_habits) * 100, 1);
        } else {
            $this->completion_rate = 0;
        }

        $this->last_calculated = now();
        $this->save();

        return $this;
    }
}
