<?php

use App\Http\Controllers\AiChatController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('habits', HabitController::class)->only(['index', 'store', 'destroy', 'update']);
    Route::post('habits/{habit}/toggle', [HabitController::class, 'toggle'])->name('habits.toggle');
    Route::get('habits-summary', [HabitController::class, 'summary'])->name('habits.summary');

    Route::get('/ai-chat', [AiChatController::class, 'index'])->name('ai-chat');
    Route::post('/ai-chat/chat', [AiChatController::class, 'chat'])->name('ai-chat.send');
    Route::post('/ai-chat/context', [AiChatController::class, 'getContext'])->name('ai-chat.context');

    Route::post('/share/create', [ShareController::class, 'create'])->name('share.create');
});

Route::get('/share/{shortCode}', [ShareController::class, 'show'])->name('share.show');

require __DIR__.'/auth.php';