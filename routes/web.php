<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;

// Landing page for guests, redirect for authenticated users
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole(['super_admin', 'admin'])) {
            return redirect('/admin');
        } else {
            return redirect()->route('tickets.index');
        }
    }
    return view('landing');
})->name('landing');

// Guest routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    Route::resource('tickets', TicketController::class)->only(['index', 'create', 'store', 'show']);
Route::get('tickets/{ticket}/comments', [CommentController::class, 'index'])->name('tickets.comments.index');
    Route::post('tickets/{ticket}/comments', [CommentController::class, 'store'])->name('tickets.comments.store');
    Route::post('tickets/{ticket}/comments/legacy', [TicketController::class, 'addComment'])->name('tickets.addComment');
});

// Include Filament routes
Route::middleware(['web'])->group(function () {
    // Filament will automatically register its routes
});
