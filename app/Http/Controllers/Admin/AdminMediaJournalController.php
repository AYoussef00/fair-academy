<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScientificArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminMediaJournalController extends Controller
{
    public function index(): Response
    {
        $pending = ScientificArticle::query()
            ->with('author:id,name,email')
            ->where('status', 'pending')
            ->latest()
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
                'author_name' => $article->author?->name,
                'author_email' => $article->author?->email,
                'created_at' => optional($article->created_at)->toDateTimeString(),
            ])
            ->values();

        $approved = ScientificArticle::query()
            ->with('author:id,name', 'reviewer:id,name')
            ->where('status', 'approved')
            ->latest('reviewed_at')
            ->take(20)
            ->get()
            ->map(fn (ScientificArticle $article) => [
                'id' => $article->id,
                'title' => $article->title,
                'researcher_name' => $article->researcher_name,
                'category' => $article->category,
                'author_name' => $article->author?->name,
                'reviewer_name' => $article->reviewer?->name,
                'reviewed_at' => optional($article->reviewed_at)->toDateTimeString(),
            ])
            ->values();

        return Inertia::render('Admin/MediaJournal', [
            'pendingArticles' => $pending,
            'approvedArticles' => $approved,
        ]);
    }

    public function approve(Request $request, ScientificArticle $article): RedirectResponse
    {
        $article->update([
            'status' => 'approved',
            'reviewed_by' => $request->user()?->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'تمت الموافقة على المقال ونشره في الموقع.');
    }

    public function reject(Request $request, ScientificArticle $article): RedirectResponse
    {
        $article->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()?->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'تم رفض المقال ولن يظهر في المجلة العامة.');
    }
}
