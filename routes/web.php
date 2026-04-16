<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCoursesController;
use App\Http\Controllers\Admin\AdminDigitalBooksController;
use App\Http\Controllers\Admin\AdminMediaJournalController;
use App\Http\Controllers\Admin\AdminProgramsController;
use App\Http\Controllers\Admin\AdminStudentsController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\CyberSecurityController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardDispatcherController;
use App\Http\Controllers\DigitalLibraryController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ScientificJournalController;
use App\Http\Controllers\Student\StudentAssignmentsController;
use App\Http\Controllers\Student\StudentAttendanceController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentExamsController;
use App\Http\Controllers\Student\StudentLiveSessionsController;
use App\Http\Controllers\Student\StudentPaymentsController;
use App\Http\Controllers\Student\StudentProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', LandingController::class)->name('home');

Route::get('/course/{id}', [CourseController::class, 'show'])->name('course.show');
Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/programs', [ProgramController::class, 'index'])->name('programs');
Route::get('/scientific-journal', [ScientificJournalController::class, 'index'])->name('scientific-journal');
Route::get('/digital-library', [DigitalLibraryController::class, 'index'])->name('digital-library');

Route::inertia('/welcome', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('welcome');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    /*
     * Dashboard dispatcher: redirects to role-specific dashboard.
     * Used as Fortify "home" and for header "Dashboard" link.
     */
    Route::get('/dashboard', DashboardDispatcherController::class)->name('dashboard');

    /*
     * Student dashboard & learning routes
     */
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', StudentDashboardController::class)->name('dashboard');
        Route::get('/courses', \App\Http\Controllers\Student\StudentCoursesController::class)->name('courses');
        Route::get('/assignments', [StudentAssignmentsController::class, 'index'])->name('assignments.index');
        Route::post('/assignments/{assignment}/submit', [StudentAssignmentsController::class, 'submit'])->name('assignments.submit');
        Route::get('/live-sessions', [StudentLiveSessionsController::class, 'index'])->name('live-sessions.index');
        Route::get('/live-sessions/{session}', [StudentLiveSessionsController::class, 'join'])->name('live-sessions.join');
        Route::get('/exams', [StudentExamsController::class, 'index'])->name('exams.index');
        Route::get('/exams/{exam}', [StudentExamsController::class, 'show'])->name('exams.show');
        Route::get('/payments', [StudentPaymentsController::class, 'index'])->name('payments.index');
        Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/cv', [StudentProfileController::class, 'uploadCv'])->name('profile.cv')->middleware('throttle:uploads');
        Route::get('/profile/cv/download', [StudentProfileController::class, 'downloadCv'])->name('profile.cv.download');
        Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/check-in', [StudentAttendanceController::class, 'checkIn'])->name('attendance.check-in');
    });

    /*
     * Instructor dashboard (Blade views)
     */
    Route::group([
        'middleware' => ['role:teacher'],
        'prefix' => 'instructor',
        'as' => 'instructor.',
    ], base_path('routes/instructor.php'));

    /*
     * Admin dashboard + إدارة الأقسام (يضيفها الأدمن فقط)
     */
    Route::middleware(['role:admin|admin_staff'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('/users', [AdminUsersController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUsersController::class, 'store'])->name('users.store');
        Route::get('/students', [AdminStudentsController::class, 'index'])->name('students.index');
        Route::get('/courses', [AdminCoursesController::class, 'index'])->name('courses.index');
        Route::get('/programs', [AdminProgramsController::class, 'index'])->name('programs.index');
        Route::get('/digital-books', [AdminDigitalBooksController::class, 'index'])->name('digital-books.index');
        Route::patch('/digital-books/{digitalBook}/approve', [AdminDigitalBooksController::class, 'approve'])->name('digital-books.approve');
        Route::patch('/digital-books/{digitalBook}/reject', [AdminDigitalBooksController::class, 'reject'])->name('digital-books.reject');
        Route::get('/media-journal', [AdminMediaJournalController::class, 'index'])->name('media-journal.index');
        Route::patch('/media-journal/{article}/approve', [AdminMediaJournalController::class, 'approve'])->name('media-journal.approve');
        Route::patch('/media-journal/{article}/reject', [AdminMediaJournalController::class, 'reject'])->name('media-journal.reject');
        Route::get('/categories', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    });

    /*
     * Cybersecurity dashboard (SOC) — admin only
     */
    Route::get('/cyber', [CyberSecurityController::class, 'index'])
        ->middleware(['auth', 'role:admin|admin_staff'])
        ->name('cyber.dashboard');

    /*
     * Cart: view, add, remove
     */
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{course}', [CartController::class, 'add'])->name('cart.add')->middleware('throttle:checkout');
    Route::delete('/cart/remove/{course}', [CartController::class, 'remove'])->name('cart.remove');

    /*
     * Checkout & payment success
     */
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:checkout');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    /*
     * Lessons & modules: require auth (enrolled users watch content)
     */
    Route::get('/course/{course}/lesson/{lesson}', [CourseController::class, 'showLesson'])->name('course.lesson');
    Route::get('/course/{course}/module/{module}', [CourseController::class, 'showModule'])->name('course.module');
    Route::post('/scientific-journal/submissions', [ScientificJournalController::class, 'store'])
        ->name('scientific-journal.submissions.store')
        ->middleware('throttle:uploads');
    Route::post('/digital-library/books', [DigitalLibraryController::class, 'store'])
        ->name('digital-library.books.store')
        ->middleware('throttle:uploads');
});

require __DIR__.'/settings.php';
