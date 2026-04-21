<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class AdminPaymentsController extends Controller
{
    public function index(): Response
    {
        $paidPaymentsQuery = Payment::query()->where('status', 'paid');
        $monthStart = Carbon::now()->startOfMonth();

        $stats = [
            'totalRevenue' => (float) (clone $paidPaymentsQuery)->sum('amount'),
            'monthRevenue' => (float) (clone $paidPaymentsQuery)->where('paid_at', '>=', $monthStart)->sum('amount'),
            'paymentsCount' => (int) (clone $paidPaymentsQuery)->count(),
            'paidInvoicesCount' => (int) Order::query()->where('status', 'paid')->count(),
        ];

        $paymentMethods = Payment::query()
            ->selectRaw('payment_method, COUNT(*) as payments_count, COALESCE(SUM(amount), 0) as total_amount')
            ->where('status', 'paid')
            ->groupBy('payment_method')
            ->orderByDesc('total_amount')
            ->get()
            ->map(fn ($row) => [
                'name' => $row->payment_method ?: 'غير محدد',
                'payments_count' => (int) $row->payments_count,
                'total_amount' => number_format((float) $row->total_amount, 2).' ر.س',
            ])
            ->values()
            ->all();

        $recentPayments = Payment::query()
            ->with([
                'order.user:id,name,email',
                'order.items',
            ])
            ->latest('paid_at')
            ->take(12)
            ->get()
            ->map(function (Payment $payment): array {
                $order = $payment->order;
                $items = $order?->items ?? collect();
                $itemsSummary = $items->isNotEmpty()
                    ? $items->map(fn ($item) => $item->title)->take(2)->implode(' - ')
                    : ($order?->description ?: 'مشتريات من المنصة');

                return [
                    'id' => $payment->id,
                    'invoice_reference' => $order?->reference ?: 'INV-'.($order?->id ?? $payment->id),
                    'customer_name' => $order?->user?->name ?? '—',
                    'customer_email' => $order?->user?->email ?? '—',
                    'items_summary' => $itemsSummary,
                    'amount' => number_format((float) $payment->amount, 2).' ر.س',
                    'payment_method' => $payment->payment_method ?: 'غير محدد',
                    'status' => $payment->status,
                    'paid_at' => $payment->paid_at?->translatedFormat('d F Y - h:i a') ?? '-',
                    'transaction_id' => $payment->transaction_id,
                ];
            })
            ->values()
            ->all();

        $recentInvoices = Order::query()
            ->with(['user:id,name,email', 'items', 'payments' => fn ($query) => $query->latest('paid_at')])
            ->latest()
            ->take(12)
            ->get()
            ->map(function (Order $order): array {
                $latestPayment = $order->payments->first();

                return [
                    'id' => $order->id,
                    'reference' => $order->reference ?: 'INV-'.$order->id,
                    'customer_name' => $order->user?->name ?? '—',
                    'description' => $order->description ?: 'فاتورة مشتريات من المنصة',
                    'items_count' => $order->items->count() > 0 ? $order->items->count() : 1,
                    'total' => number_format((float) $order->total, 2).' ر.س',
                    'status' => $order->status,
                    'payment_method' => $latestPayment?->payment_method ?: '—',
                    'created_at' => $order->created_at?->translatedFormat('d F Y') ?? '-',
                ];
            })
            ->values()
            ->all();

        return Inertia::render('Admin/Payments', [
            'stats' => $stats,
            'paymentMethods' => $paymentMethods,
            'recentPayments' => $recentPayments,
            'recentInvoices' => $recentInvoices,
        ]);
    }
}
