<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    public function progressSummary()
    {
        return $this->hasOne(ProgressSummary::class);
    }

    public function getProgressSummary()
    {
        $summary = $this->progressSummary;

        if (! $summary) {
            $summary = ProgressSummary::create([
                'user_id' => $this->id,
                'total_habits' => 0,
                'completed_today' => 0,
                'completion_rate' => 0,
            ]);
        }

        return $summary->recalculate();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
