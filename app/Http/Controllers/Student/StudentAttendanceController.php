<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentAttendanceController extends Controller
{
    public function index(Request $request): Response
    {
        $records = [
            [
                'id' => 1,
                'date_human' => '10 مارس 2026',
                'course_title' => 'برنامج الدبلوم المهني في علوم البيانات',
                'session_title' => 'المحاضرة الأولى: مقدمة في البيانات',
                'status' => 'present',
            ],
            [
                'id' => 2,
                'date_human' => '12 مارس 2026',
                'course_title' => 'برنامج الدبلوم المهني في علوم البيانات',
                'session_title' => 'المحاضرة الثانية: أنواع البيانات',
                'status' => 'present',
            ],
            [
                'id' => 3,
                'date_human' => '14 مارس 2026',
                'course_title' => 'برنامج الدبلوم المهني في علوم البيانات',
                'session_title' => 'المحاضرة الثالثة: أساسيات Python',
                'status' => 'absent',
            ],
            [
                'id' => 4,
                'date_human' => '17 مارس 2026',
                'course_title' => 'برنامج الدبلوم المهني في علوم البيانات',
                'session_title' => 'جلسة مباشرة: تطبيق عملي على تحليل البيانات',
                'status' => 'present',
            ],
        ];

        $present = collect($records)->where('status', 'present')->count();
        $attendancePercent = count($records) > 0 ? round(($present / count($records)) * 100) : 0;

        return Inertia::render('Student/Attendance', [
            'records' => $records,
            'attendancePercent' => $attendancePercent,
        ]);
    }

    public function checkIn(Request $request): RedirectResponse
    {
        return back()->with('success', 'تم تسجيل حضورك بنجاح لهذه الجلسة.');
    }
}
