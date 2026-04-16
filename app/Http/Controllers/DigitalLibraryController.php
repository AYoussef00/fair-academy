<?php

namespace App\Http\Controllers;

use App\Models\DigitalBook;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DigitalLibraryController extends Controller
{
    public function index(Request $request): Response
    {
        $approvedBooks = DigitalBook::query()
            ->where('status', 'approved')
            ->latest()
            ->get()
            ->map(fn (DigitalBook $book) => [
                'id' => $book->id,
                'title' => $book->title,
                'cover' => $book->cover_path ? Storage::url($book->cover_path) : null,
                'price' => (float) $book->price,
                'purchase_count' => (int) $book->purchase_count,
                'status' => $book->status,
            ])
            ->values();

        return Inertia::render('DigitalLibrary', [
            'approvedBooks' => $approvedBooks,
            'isAuthenticated' => $request->user() !== null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $coverFile = $request->file('cover');
        if (! $coverFile || ! $coverFile->isValid()) {
            return back()->withErrors([
                'cover' => 'تعذر رفع صورة الغلاف. تأكد أن الملف بصيغة JPG/PNG/WEBP وحجمه مناسب ثم أعد المحاولة.',
            ])->withInput();
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'cover' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'cover.uploaded' => 'تعذر رفع صورة الغلاف من السيرفر. جرّب صورة أصغر ثم أعد المحاولة.',
        ]);

        $coverPath = $coverFile->store('digital-books/covers', 'public');

        DigitalBook::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'cover_path' => $coverPath,
            'price' => (float) $validated['price'],
            'purchase_count' => 0,
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال الكتاب للمراجعة بنجاح.');
    }
}
