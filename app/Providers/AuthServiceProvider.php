<?php

namespace App\Providers;

use App\Models\Habit;
use App\Policies\HabitPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Habit::class => HabitPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
