<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentExamsController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $exams = [
            [
                'id' => 1,
                'course_title' => 'أساسيات الذكاء الاصطناعي',
                'title' => 'اختبار الوحدة الأولى: المفاهيم الأساسية',
                'status' => 'upcoming',
                'date_human' => '20 مارس 2026',
                'time_human' => '7:00 م – 7:45 م',
                'duration_minutes' => 45,
                'total_questions' => 20,
                'score' => null,
            ],
            [
                'id' => 2,
                'course_title' => 'برمجة بايثون المتقدمة',
                'title' => 'كويز قصير: الحلقات والقوائم',
                'status' => 'live',
                'date_human' => 'اليوم',
                'time_human' => '8:00 م – 8:20 م',
                'duration_minutes' => 20,
                'total_questions' => 10,
                'score' => null,
            ],
            [
                'id' => 3,
                'course_title' => 'تحليل البيانات',
                'title' => 'الاختبار النهائي',
                'status' => 'completed',
                'date_human' => '10 مارس 2026',
                'time_human' => '6:00 م – 6:45 م',
                'duration_minutes' => 45,
                'total_questions' => 25,
                'score' => '85%',
            ],
        ];

        return Inertia::render('Student/Exams', [
            'exams' => $exams,
            'userName' => $user->name ?? 'الطالب',
        ]);
    }

    public function show(Request $request, int $exam): Response
    {
        $user = $request->user();

        $examData = [
            'id' => $exam,
            'course_title' => 'أساسيات الذكاء الاصطناعي',
            'title' => 'اختبار الوحدة الأولى: المفاهيم الأساسية',
            'duration_minutes' => 45,
            'total_questions' => 5,
            'description' => 'اختبار قصير لقياس فهمك للمفاهيم الأساسية في الذكاء الاصطناعي قبل الانتقال للوحدة التالية.',
            'questions' => [
                [
                    'id' => 1,
                    'type' => 'mcq',
                    'text' => 'أي من التالي يُعتبر تعريفاً مناسباً للذكاء الاصطناعي؟',
                    'options' => [
                        'جعل الأجهزة تقوم بمهام تتطلّب ذكاءً بشرياً',
                        'كتابة كود بلغة بايثون فقط',
                        'تصميم واجهات المستخدم',
                        'تخزين البيانات في قاعدة البيانات',
                    ],
                    'correct_index' => 0,
                    'points' => 20,
                ],
                [
                    'id' => 2,
                    'type' => 'true_false',
                    'text' => 'خوارزميات تعلم الآلة هي جزء من الذكاء الاصطناعي.',
                    'correct_boolean' => true,
                    'points' => 20,
                ],
                [
                    'id' => 3,
                    'type' => 'true_false',
                    'text' => 'الشبكات العصبية العميقة لا تُستخدم في رؤية الحاسوب.',
                    'correct_boolean' => false,
                    'points' => 20,
                ],
                [
                    'id' => 4,
                    'type' => 'mcq',
                    'text' => 'أي من التالي مثال على تطبيقات الذكاء الاصطناعي في الحياة اليومية؟',
                    'options' => [
                        'محرر النصوص العادي',
                        'أنظمة التوصية في منصات الفيديو',
                        'الآلة الحاسبة التقليدية',
                        'ملف إكسل فارغ',
                    ],
                    'correct_index' => 1,
                    'points' => 20,
                ],
                [
                    'id' => 5,
                    'type' => 'essay',
                    'text' => 'في سطور قليلة، اشرح كيف يمكن للذكاء الاصطناعي أن يحسّن تجربة الطالب في منصات التعلم الإلكتروني.',
                    'max_words' => 120,
                    'points' => 20,
                ],
            ],
        ];

        return Inertia::render('Student/ExamTake', [
            'exam' => $examData,
            'userName' => $user->name ?? 'الطالب',
        ]);
    }
}
