<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Inertia\Inertia;
use Inertia\Response;

class AdminProgramsController extends Controller
{
    public function index(): Response
    {
        $programs = Program::query()
            ->withCount('courses')
            ->with(['courses' => fn ($query) => $query->withCount('enrollments')])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Program $program) => [
                'id' => $program->id,
                'title' => $program->title,
                'price' => (float) $program->price,
                'duration' => $program->duration,
                'level' => $program->level,
                'status' => $program->status,
                'courses_count' => (int) $program->courses_count,
                'students_count' => (int) $program->courses->sum('enrollments_count'),
                'created_at' => optional($program->created_at)->toDateString(),
            ])
            ->values();

        return Inertia::render('Admin/Programs', [
            'programs' => $programs,
        ]);
    }
}
