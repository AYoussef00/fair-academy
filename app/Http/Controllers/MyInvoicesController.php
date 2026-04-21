<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\BackfillLegacyOrderItems;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MyInvoicesController extends Controller
{
    public function index(Request $request, BackfillLegacyOrderItems $backfillLegacyOrderItems): Response
    {
        $backfillLegacyOrderItems->forUser($request->user());

        $invoices = Order::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'items',
                'payments' => fn ($query) => $query->latest('paid_at'),
            ])
            ->latest()
            ->get()
            ->map(fn (Order $order) => $this->transformInvoiceSummary($order))
            ->values()
            ->all();

        return Inertia::render('MyInvoices', [
            'invoices' => $invoices,
        ]);
    }

    public function show(Request $request, Order $order, BackfillLegacyOrderItems $backfillLegacyOrderItems): Response
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        $backfillLegacyOrderItems->forUser($request->user());

        $order->load([
            'user:id,name,email',
            'items',
            'payments' => fn ($query) => $query->latest('paid_at'),
        ]);

        return Inertia::render('MyInvoiceShow', [
            'invoice' => $this->transformInvoiceDetails($order),
        ]);
    }

    private function transformInvoiceSummary(Order $order): array
    {
        $latestPayment = $order->payments->first();
        $items = $this->transformItems($order);

        return [
            'id' => $order->id,
            'reference' => $order->reference ?: 'INV-'.$order->id,
            'description' => $order->description ?: 'فاتورة مشتريات من المنصة',
            'amount' => number_format((float) $order->total, 2).' ر.س',
            'status' => $order->status === 'paid' ? 'paid' : 'unpaid',
            'created_at' => optional($order->created_at)->translatedFormat('d F Y - h:i a') ?? '-',
            'paid_at' => $latestPayment?->paid_at?->translatedFormat('d F Y - h:i a'),
            'payment_method' => $latestPayment?->payment_method,
            'items_count' => count($items),
            'view_url' => route('my-invoices.show', $order),
        ];
    }

    private function transformInvoiceDetails(Order $order): array
    {
        $latestPayment = $order->payments->first();
        $items = $this->transformItems($order);

        return [
            'id' => $order->id,
            'reference' => $order->reference ?: 'INV-'.$order->id,
            'description' => $order->description ?: 'فاتورة مشتريات من المنصة',
            'amount' => number_format((float) $order->total, 2).' ر.س',
            'subtotal' => number_format((float) $order->total, 2).' ر.س',
            'status' => $order->status === 'paid' ? 'paid' : 'unpaid',
            'created_at' => optional($order->created_at)->translatedFormat('d F Y - h:i a') ?? '-',
            'paid_at' => $latestPayment?->paid_at?->translatedFormat('d F Y - h:i a'),
            'payment_method' => $latestPayment?->payment_method ?: 'غير محدد',
            'transaction_id' => $latestPayment?->transaction_id,
            'customer_name' => $order->user?->name ?? 'العميل',
            'customer_email' => $order->user?->email ?? '-',
            'items' => $items,
        ];
    }

    private function transformItems(Order $order): array
    {
        if ($order->items->isNotEmpty()) {
            return $order->items
                ->map(fn ($item) => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => $item->item_type === 'book' ? 'كتاب رقمي' : 'دورة',
                    'price' => number_format((float) $item->price, 2).' ر.س',
                    'quantity' => (int) $item->quantity,
                ])
                ->values()
                ->all();
        }

        return [[
            'id' => $order->id,
            'title' => $order->description ?: 'مشتريات من المنصة',
            'type' => 'مشتريات',
            'price' => number_format((float) $order->total, 2).' ر.س',
            'quantity' => 1,
        ]];
    }
}
