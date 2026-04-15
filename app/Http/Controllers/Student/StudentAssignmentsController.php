<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentAssignmentsController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $assignments = [
            [
                'id' => 1,
                'course_title' => 'مقدمة في الذكاء الاصطناعي',
                'title' => 'مشروع: تحليل بيانات باستخدام بايثون',
                'description' => 'استخدم بايثون لتحليل ملف بيانات ورفع تقرير PDF بالنتائج.',
                'due_in_text' => 'باقي 3 أيام',
                'due_date_human' => '20 مارس 2026',
                'status' => 'pending',
                'grade' => null,
                'feedback' => null,
                'submitted' => false,
            ],
            [
                'id' => 2,
                'course_title' => 'أساسيات تطوير الويب',
                'title' => 'واجب: تصميم صفحة هبوط',
                'description' => 'صمّم صفحة هبوط كاملة باستخدام HTML و Tailwind ورفع الملف في ملف ZIP.',
                'due_in_text' => 'باقي يوم واحد',
                'due_date_human' => '18 مارس 2026',
                'status' => 'submitted',
                'grade' => null,
                'feedback' => 'في انتظار التصحيح من المدرب.',
                'submitted' => true,
            ],
            [
                'id' => 3,
                'course_title' => 'برمجة بايثون المتقدمة',
                'title' => 'واجب: بناء REST API بسيط',
                'description' => 'قم ببناء REST API بسيط وتوثيقه، ثم ارفع ملف PDF يشرح الكود.',
                'due_in_text' => 'انتهى منذ 2 يوم',
                'due_date_human' => '10 مارس 2026',
                'status' => 'graded',
                'grade' => '95/100',
                'feedback' => 'عمل ممتاز! الكود منظم والتوثيق واضح جدًا.',
                'submitted' => true,
            ],
        ];

        return Inertia::render('Student/Assignments', [
            'assignments' => $assignments,
            'userName' => $user->name ?? 'الطالب',
        ]);
    }

    public function submit(Request $request, int $assignment): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,zip', 'max:10240'],
        ]);

        $user = $request->user();

        $request->file('file')->storeAs(
            'student-assignments/'.$user->id,
            now()->format('Ymd_His').'_assignment_'.$assignment.'.'.$request->file('file')->getClientOriginalExtension(),
            'public'
        );

        return back()->with('success', 'تم رفع الواجب بنجاح. سيتم مراجعته من قِبل المدرب قريبًا.');
    }
}
