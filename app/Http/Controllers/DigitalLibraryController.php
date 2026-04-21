<?php

namespace App\Http\Controllers;

use App\Models\DigitalBook;
use App\Models\OrderItem;
use App\Services\BackfillLegacyOrderItems;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DigitalLibraryController extends Controller
{
    public function index(Request $request, BackfillLegacyOrderItems $backfillLegacyOrderItems): Response
    {
        $purchasedBookIds = [];

        if ($request->user() !== null) {
            $backfillLegacyOrderItems->forUser($request->user());

            $purchasedBookIds = OrderItem::query()
                ->where('item_type', 'book')
                ->whereHas('order', fn ($query) => $query
                    ->where('user_id', $request->user()->id)
                    ->where('status', 'paid'))
                ->pluck('item_id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        $approvedBooks = DigitalBook::query()
            ->where('status', 'approved')
            ->latest()
            ->get()
            ->map(fn (DigitalBook $book) => $this->transformBook($book, in_array($book->id, $purchasedBookIds, true)))
            ->values();

        return Inertia::render('DigitalLibrary', [
            'approvedBooks' => $approvedBooks,
            'isAuthenticated' => $request->user() !== null,
        ]);
    }

    public function show(Request $request, DigitalBook $digitalBook, BackfillLegacyOrderItems $backfillLegacyOrderItems): Response
    {
        abort_if($digitalBook->status !== 'approved', 404);

        if ($request->user() !== null) {
            $backfillLegacyOrderItems->forUser($request->user());
        }

        $isPurchased = $request->user() !== null
            && OrderItem::query()
                ->where('item_type', 'book')
                ->where('item_id', $digitalBook->id)
                ->whereHas('order', fn ($query) => $query
                    ->where('user_id', $request->user()->id)
                    ->where('status', 'paid'))
                ->exists();

        return Inertia::render('DigitalBookDetails', [
            'book' => $this->transformBook($digitalBook),
            'isAuthenticated' => $request->user() !== null,
            'isPurchased' => $isPurchased,
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

    private function transformBook(DigitalBook $book, bool $isPurchased = false): array
    {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'cover' => $book->cover_path ? Storage::url($book->cover_path) : null,
            'price' => (float) $book->price,
            'purchase_count' => (int) $book->purchase_count,
            'status' => $book->status,
            'is_purchased' => $isPurchased,
        ];
    }
}
