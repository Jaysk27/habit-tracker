<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitTracking extends Model
{
    use HasFactory;

    protected $table = 'habit_tracking';

    protected $fillable = ['habit_id', 'completed_date'];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}
