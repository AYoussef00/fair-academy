<?php

use App\Http\Controllers\Instructor\InstructorAnalyticsController;
use App\Http\Controllers\Instructor\InstructorCourseController;
use App\Http\Controllers\Instructor\InstructorDashboardController;
use App\Http\Controllers\Instructor\InstructorLessonController;
use App\Http\Controllers\Instructor\InstructorLiveClassController;
use App\Http\Controllers\Instructor\InstructorModuleController;
use App\Http\Controllers\Instructor\InstructorQuizController;
use App\Http\Controllers\Instructor\InstructorStudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Instructor Dashboard Routes
| All under /instructor, middleware: auth, verified, role:teacher
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', InstructorDashboardController::class)->name('dashboard');

// Courses
Route::get('/courses', [InstructorCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/create', [InstructorCourseController::class, 'create'])->name('courses.create');
Route::post('/courses', [InstructorCourseController::class, 'store'])->name('courses.store');
Route::get('/courses/{course}', [InstructorCourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{course}/edit', [InstructorCourseController::class, 'edit'])->name('courses.edit');
Route::put('/courses/{course}', [InstructorCourseController::class, 'update'])->name('courses.update');
Route::delete('/courses/{course}', [InstructorCourseController::class, 'destroy'])->name('courses.destroy');
Route::get('/courses/{course}/builder', [InstructorCourseController::class, 'builder'])->name('courses.builder');
Route::post('/courses/{course}/publish', [InstructorCourseController::class, 'publish'])->name('courses.publish');

// Course builder: modules
Route::post('/courses/{course}/modules', [InstructorModuleController::class, 'store'])->name('modules.store');
Route::put('/courses/{course}/modules/{module}', [InstructorModuleController::class, 'update'])->name('modules.update');
Route::delete('/courses/{course}/modules/{module}', [InstructorModuleController::class, 'destroy'])->name('modules.destroy');
Route::post('/courses/{course}/modules/reorder', [InstructorModuleController::class, 'reorder'])->name('modules.reorder');

// Course builder: lessons
Route::post('/courses/{course}/lessons', [InstructorLessonController::class, 'store'])->name('lessons.store');
Route::get('/courses/{course}/lessons/{lesson}/edit', [InstructorLessonController::class, 'edit'])->name('lessons.edit');
Route::put('/courses/{course}/lessons/{lesson}', [InstructorLessonController::class, 'update'])->name('lessons.update');
Route::delete('/courses/{course}/lessons/{lesson}', [InstructorLessonController::class, 'destroy'])->name('lessons.destroy');
Route::post('/courses/{course}/lessons/reorder', [InstructorLessonController::class, 'reorder'])->name('lessons.reorder');

// Students
Route::get('/students', [InstructorStudentController::class, 'index'])->name('students.index');
Route::get('/students/{enrollmentId}', [InstructorStudentController::class, 'show'])->name('students.show');

// Quizzes
Route::get('/quizzes', [InstructorQuizController::class, 'index'])->name('quizzes.index');
Route::get('/quizzes/create', [InstructorQuizController::class, 'create'])->name('quizzes.create');
Route::post('/quizzes', [InstructorQuizController::class, 'store'])->name('quizzes.store');
Route::get('/quizzes/{quiz}', [InstructorQuizController::class, 'show'])->name('quizzes.show');
Route::get('/quizzes/{quiz}/edit', [InstructorQuizController::class, 'edit'])->name('quizzes.edit');
Route::put('/quizzes/{quiz}', [InstructorQuizController::class, 'update'])->name('quizzes.update');
Route::delete('/quizzes/{quiz}', [InstructorQuizController::class, 'destroy'])->name('quizzes.destroy');

// Live classes
Route::get('/live-classes', [InstructorLiveClassController::class, 'index'])->name('live-classes.index');
Route::get('/live-classes/create', [InstructorLiveClassController::class, 'create'])->name('live-classes.create');
Route::post('/live-classes', [InstructorLiveClassController::class, 'store'])->name('live-classes.store');
Route::get('/live-classes/{live_class}/edit', [InstructorLiveClassController::class, 'edit'])->name('live-classes.edit');
Route::put('/live-classes/{live_class}', [InstructorLiveClassController::class, 'update'])->name('live-classes.update');
Route::delete('/live-classes/{live_class}', [InstructorLiveClassController::class, 'destroy'])->name('live-classes.destroy');

// Analytics
Route::get('/analytics', [InstructorAnalyticsController::class, 'index'])->name('analytics.index');
