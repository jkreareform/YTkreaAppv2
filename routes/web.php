<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatGPTController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

// Chat-Routen (öffentlich)
Route::post('/chat/send', [ChatController::class, 'send']);
Route::get('/chat/prompt-library', [ChatController::class, 'getPromptLibrary']);

// ChatGPT API Route
Route::post('/api/chat', [ChatGPTController::class, 'sendMessage']);

// Einfache Test-Route für direkten API-Zugriff
Route::get('/test-chat', function () {
    return view('test-chat');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});

Route::middleware('supabase.auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::view('profile', 'profile')->name('profile');
});
