<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'usersCount' => User::query()->count(),
                'coursesCount' => Course::query()->count(),
                'programsCount' => Program::query()->count(),
            ],
        ]);
    }
}
