<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $data = [];

    if ($user->role === 'admin') {
        $data = [
            'total_projects' => App\Models\Project::count(),
            'total_users' => App\Models\User::count(),
            'total_tasks' => App\Models\Task::count(),
            'my_pending_tasks' => App\Models\Task::where('status', '!=', 'completed')->count(), 
        ];
    } else {
        // Normal User
        $data = [
            'total_projects' => $user->projects()->count(),
            'total_users' => null, // Hidden
            'total_tasks' => App\Models\Task::whereHas('project', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'my_pending_tasks' => App\Models\Task::whereHas('project', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', '!=', 'completed')->count(),
        ];
    }
    
    return view('dashboard', ['stats' => $data]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
});

require __DIR__.'/auth.php';
