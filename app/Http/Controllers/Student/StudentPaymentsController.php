<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentPaymentsController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $invoices = [
            [
                'id' => 1,
                'reference' => 'INV-2026-001',
                'course_title' => 'برنامج الدبلوم المهني في علوم البيانات',
                'amount' => '2,500.00 SAR',
                'due_date_human' => '20 مارس 2026',
                'status' => 'unpaid',
                'payment_method' => null,
                'paid_at' => null,
                'receipt_url' => null,
            ],
            [
                'id' => 2,
                'reference' => 'INV-2026-002',
                'course_title' => 'دورة أساسيات الذكاء الاصطناعي',
                'amount' => '599.00 SAR',
                'due_date_human' => '10 مارس 2026',
                'status' => 'paid',
                'payment_method' => 'بطاقة ائتمانية (Visa **** 1234)',
                'paid_at' => '10 مارس 2026 - 08:15 م',
                'receipt_url' => '#',
            ],
            [
                'id' => 3,
                'reference' => 'INV-2026-003',
                'course_title' => 'جلسات تدريب فردية في البرمجة',
                'amount' => '1,200.00 SAR',
                'due_date_human' => '01 مارس 2026',
                'status' => 'overdue',
                'payment_method' => null,
                'paid_at' => null,
                'receipt_url' => null,
            ],
        ];

        return Inertia::render('Student/Payments', [
            'invoices' => $invoices,
            'userName' => $user->name ?? 'الطالب',
        ]);
    }
}
