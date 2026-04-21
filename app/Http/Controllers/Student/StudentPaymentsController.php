<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentPaymentsController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $invoices = Order::query()
            ->where('user_id', $user->id)
            ->with(['payments' => fn ($query) => $query->latest('paid_at')])
            ->latest()
            ->get()
            ->map(function (Order $order): array {
                $latestPayment = $order->payments->first();

                return [
                    'id' => $order->id,
                    'reference' => $order->reference ?: 'INV-'.$order->id,
                    'course_title' => $order->description ?: 'فاتورة مشتريات من المنصة',
                    'amount' => number_format((float) $order->total, 2).' SAR',
                    'due_date_human' => optional($order->created_at)->translatedFormat('d F Y') ?? '-',
                    'status' => $order->status === 'paid' ? 'paid' : 'unpaid',
                    'payment_method' => $latestPayment?->payment_method,
                    'paid_at' => $latestPayment?->paid_at?->translatedFormat('d F Y - h:i a'),
                    'receipt_url' => null,
                ];
            })
            ->values()
            ->all();

        return Inertia::render('Student/Payments', [
            'invoices' => $invoices,
            'userName' => $user->name ?? 'الطالب',
        ]);
    }
}
