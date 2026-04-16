<?php

namespace App\Http\Controllers;

use App\Models\ScientificArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ScientificJournalController extends Controller
{
    public function index(): Response
    {
        $articles = ScientificArticle::query()
            ->with('author:id,name')
            ->where('status', 'approved')
            ->latest()
            ->take(24)
            ->get()
            ->map(fn (ScientificArticle $article) => [
                'id' => $article->id,
                'title' => $article->title,
                'researcher_name' => $article->researcher_name,
                'category' => $article->category,
                'excerpt' => $article->excerpt,
                'keywords' => $article->keywords,
                'content' => $article->content,
                'pdf_url' => $article->pdf_path ? Storage::url($article->pdf_path) : null,
                'author' => $article->author?->name,
                'published_at' => optional($article->reviewed_at ?? $article->created_at)->toDateString(),
            ])
            ->values();

        return Inertia::render('ScientificJournal', [
            'articles' => $articles,
            'canSubmit' => request()->user() !== null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'researcher_name' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:600'],
            'keywords' => ['required', 'string', 'max:800'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $pdfPath = $request->file('pdf')?->store('scientific-articles/pdfs', 'public');

        ScientificArticle::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'researcher_name' => $validated['researcher_name'],
            'category' => 'بحث علمي',
            'excerpt' => $validated['excerpt'],
            'keywords' => $validated['keywords'],
            'pdf_path' => $pdfPath,
            'content' => $validated['excerpt'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال البحث والمرفق PDF للمراجعة بنجاح.');
    }
}
