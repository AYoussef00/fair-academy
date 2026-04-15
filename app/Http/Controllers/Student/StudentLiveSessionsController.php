<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentLiveSessionsController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $sessions = [
            [
                'id' => 1,
                'course_title' => 'برنامج علوم البيانات المتقدمة',
                'topic' => 'تحليل البيانات الضخمة باستخدام Python',
                'date_human' => '20 مارس 2026',
                'time_range' => '7:00 م – 9:00 م',
                'status' => 'upcoming',
                'instructor' => 'د. أحمد علي',
                'platform' => 'Zoom',
            ],
            [
                'id' => 2,
                'course_title' => 'أساسيات الذكاء الاصطناعي',
                'topic' => 'الشبكات العصبية العميقة – تطبيق عملي',
                'date_human' => 'اليوم',
                'time_range' => '8:00 م – 10:00 م',
                'status' => 'live',
                'instructor' => 'د. سارة محمد',
                'platform' => 'Zoom',
            ],
            [
                'id' => 3,
                'course_title' => 'تطوير واجهات الويب',
                'topic' => 'تصميم Dashboard تفاعلية باستخدام Vue.js',
                'date_human' => '15 مارس 2026',
                'time_range' => '6:00 م – 8:00 م',
                'status' => 'completed',
                'instructor' => 'م. يوسف سامي',
                'platform' => 'Google Meet',
            ],
        ];

        return Inertia::render('Student/LiveSessions', [
            'sessions' => $sessions,
            'userName' => $user->name ?? 'الطالب',
        ]);
    }

    public function join(Request $request, int $session): Response
    {
        $user = $request->user();

        $all = [
            [
                'id' => 1,
                'course_title' => 'برنامج علوم البيانات المتقدمة',
                'topic' => 'تحليل البيانات الضخمة باستخدام Python',
                'date_human' => '20 مارس 2026',
                'time_range' => '7:00 م – 9:00 م',
                'status' => 'upcoming',
                'instructor' => 'د. أحمد علي',
                'platform' => 'Zoom',
                'description' => 'جلسة تفاعلية نطبّق فيها عملياً على تحليل مجموعات بيانات ضخمة باستخدام مكتبات بايثون الشهيرة.',
            ],
            [
                'id' => 2,
                'course_title' => 'أساسيات الذكاء الاصطناعي',
                'topic' => 'الشبكات العصبية العميقة – تطبيق عملي',
                'date_human' => 'اليوم',
                'time_range' => '8:00 م – 10:00 م',
                'status' => 'live',
                'instructor' => 'د. سارة محمد',
                'platform' => 'Zoom',
                'description' => 'جلسة مباشرة نشرح فيها مفاهيم الـ Deep Learning مع عرض توضيحي مباشر للنماذج.',
            ],
            [
                'id' => 3,
                'course_title' => 'تطوير واجهات الويب',
                'topic' => 'تصميم Dashboard تفاعلية باستخدام Vue.js',
                'date_human' => '15 مارس 2026',
                'time_range' => '6:00 م – 8:00 م',
                'status' => 'completed',
                'instructor' => 'م. يوسف سامي',
                'platform' => 'Google Meet',
                'description' => 'تم في هذه الجلسة بناء لوحة تحكم متكاملة باستخدام Vue.js و Tailwind.',
            ],
        ];

        $current = collect($all)->firstWhere('id', $session);

        abort_if(! $current, 404);

        return Inertia::render('Student/LiveSessionRoom', [
            'session' => $current,
            'userName' => $user->name ?? 'الطالب',
        ]);
    }
}
