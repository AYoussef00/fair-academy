<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::query()
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Program $p) => [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'thumbnail' => $p->thumbnail
                    ? (str_starts_with($p->thumbnail, 'http') ? $p->thumbnail : Storage::url($p->thumbnail))
                    : null,
                'price' => (float) $p->price,
                'duration' => $p->duration,
                'level' => $p->level,
                'description' => $p->description,
            ])
            ->values();

        return Inertia::render('Programs', [
            'programs' => $programs,
        ]);
    }
}
