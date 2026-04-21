<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Services\BackfillLegacyOrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class MyBooksController extends Controller
{
    public function index(Request $request, BackfillLegacyOrderItems $backfillLegacyOrderItems): Response
    {
        $backfillLegacyOrderItems->forUser($request->user());

        $books = OrderItem::query()
            ->with([
                'order:id,user_id,reference,created_at,status',
                'digitalBook:id,title,cover_path,price,status',
            ])
            ->where('item_type', 'book')
            ->whereHas('order', fn ($query) => $query
                ->where('user_id', $request->user()->id)
                ->where('status', 'paid'))
            ->latest('id')
            ->get()
            ->filter(fn (OrderItem $item) => $item->digitalBook !== null)
            ->unique('item_id')
            ->values()
            ->map(fn (OrderItem $item) => [
                'id' => (int) $item->digitalBook->id,
                'title' => $item->digitalBook->title,
                'cover' => $item->digitalBook->cover_path ? Storage::url($item->digitalBook->cover_path) : null,
                'price' => (float) $item->price,
                'purchased_at' => optional($item->order?->created_at)->translatedFormat('d F Y') ?? '-',
                'invoice_reference' => $item->order?->reference ?: 'INV-'.($item->order?->id ?? $item->id),
                'detail_url' => route('digital-library.books.show', $item->digitalBook),
            ])
            ->all();

        return Inertia::render('MyBooks', [
            'books' => $books,
        ]);
    }
}
