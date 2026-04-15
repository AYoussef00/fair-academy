<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Program;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class LandingController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name]);

        $courses = Course::query()
            ->with(['category:id,name', 'instructor:id,name'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'thumbnail' => $c->thumbnail,
                'price' => (float) $c->price,
                'category_id' => $c->category_id,
                'category_name' => $c->category->name ?? '',
                'instructor' => $c->instructor->name ?? '',
                'status' => $c->status ?? 'draft',
            ])
            ->values()
            ->all();

        $programs = Program::query()
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'thumbnail', 'price', 'duration', 'level'])
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'thumbnail' => $p->thumbnail,
                'price' => (float) $p->price,
                'duration' => $p->duration,
                'level' => $p->level,
            ])
            ->values()
            ->all();

        return Inertia::render('Landing', [
            'canRegister' => Features::enabled(Features::registration()),
            'categories' => $categories,
            'courses' => $courses,
            'programs' => $programs,
        ]);
    }
}
