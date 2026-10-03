<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareLink extends Model
{
    protected $fillable = [
        'short_code',
        'user_id',
        'habit_id',
        'year',
        'month',
        'completion_rate',
        'current_streak',
        'total_completions',
        'og_image_path',
        'views',
        'expires_at',
    ];

    protected $casts = [
        'completion_rate' => 'float',
        'current_streak' => 'integer',
        'total_completions' => 'integer',
        'views' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }

    public static function generateShortCode(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (self::where('short_code', $code)->exists());
        
        return $code;
    }

    public function getShareUrlAttribute(): string
    {
        return url('/share/' . $this->short_code);
    }
}