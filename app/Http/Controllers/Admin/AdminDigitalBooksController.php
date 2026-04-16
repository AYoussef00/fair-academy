<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalBook;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminDigitalBooksController extends Controller
{
    public function index(): Response
    {
        $pendingBooks = DigitalBook::query()
            ->with('owner:id,name,email')
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->map(fn (DigitalBook $book) => [
                'id' => $book->id,
                'title' => $book->title,
                'cover' => $book->cover_path ? Storage::url($book->cover_path) : null,
                'price' => (float) $book->price,
                'purchase_count' => (int) $book->purchase_count,
                'owner_name' => $book->owner?->name,
                'owner_email' => $book->owner?->email,
                'created_at' => optional($book->created_at)->toDateTimeString(),
            ])
            ->values();

        $approvedBooks = DigitalBook::query()
            ->with('owner:id,name', 'reviewer:id,name')
            ->where('status', 'approved')
            ->latest('reviewed_at')
            ->take(30)
            ->get()
            ->map(fn (DigitalBook $book) => [
                'id' => $book->id,
                'title' => $book->title,
                'price' => (float) $book->price,
                'purchase_count' => (int) $book->purchase_count,
                'owner_name' => $book->owner?->name,
                'reviewer_name' => $book->reviewer?->name,
                'reviewed_at' => optional($book->reviewed_at)->toDateTimeString(),
            ])
            ->values();

        return Inertia::render('Admin/DigitalBooks', [
            'pendingBooks' => $pendingBooks,
            'approvedBooks' => $approvedBooks,
        ]);
    }

    public function approve(Request $request, DigitalBook $digitalBook): RedirectResponse
    {
        $digitalBook->update([
            'status' => 'approved',
            'reviewed_by' => $request->user()?->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'تمت الموافقة على الكتاب بنجاح.');
    }

    public function reject(Request $request, DigitalBook $digitalBook): RedirectResponse
    {
        $digitalBook->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()?->id,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'تم رفض الكتاب.');
    }
}
