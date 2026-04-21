<?php

namespace App\Services;

use App\Models\DigitalBook;
use App\Models\Order;
use App\Models\User;

class BackfillLegacyOrderItems
{
    public function forUser(User $user): void
    {
        Order::query()
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->doesntHave('items')
            ->get()
            ->each(function (Order $order): void {
                $description = trim((string) $order->description);
                if ($description === '') {
                    return;
                }

                $matchingBooks = DigitalBook::query()
                    ->where('status', 'approved')
                    ->where('title', $description)
                    ->where('price', (float) $order->total)
                    ->get();

                if ($matchingBooks->count() !== 1) {
                    return;
                }

                $book = $matchingBooks->first();

                $order->items()->firstOrCreate([
                    'item_type' => 'book',
                    'item_id' => $book->id,
                ], [
                    'title' => $book->title,
                    'price' => (float) $book->price,
                    'quantity' => 1,
                ]);
            });
    }
}
