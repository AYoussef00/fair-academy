<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CyberSecurityService;
use Illuminate\View\View;

class CyberSecurityController extends Controller
{
    public function __construct(
        protected CyberSecurityService $service
    ) {}

    public function index(): View
    {
        $vulnerabilities = [
            [
                'type' => 'Security Misconfiguration',
                'risk' => 'High',
                'file' => '.env',
                'description' => 'APP_DEBUG مفعّل مع بيانات اتصال قاعدة بيانات حقيقية في ملف بيئة يمكن أن يُرفع إلى المستودع.',
                'status' => 'fixed',
                'recommendation' => 'تعطيل APP_DEBUG في بيئة الإنتاج، واستخدام كلمات مرور قوية، وعدم رفع ملفات .env إلى Git.',
            ],
            [
                'type' => 'Sensitive Data Exposure',
                'risk' => 'High',
                'file' => '.env',
                'description' => 'بيانات اتصال قاعدة البيانات ظاهرة بكلمات سر ضعيفة.',
                'status' => 'fixed',
                'recommendation' => 'تغيير اسم المستخدم وكلمة المرور إلى قيم قوية، وحصر صلاحيات الحساب، واستخدام أسرار من مدير أسرار. (.env.example موثّق و .env غير مرفوع في Git)',
            ],
            [
                'type' => 'File Upload',
                'risk' => 'Medium',
                'file' => 'app/Http/Controllers/Student/StudentProfileController.php',
                'description' => 'رفع ملفات CV يتم تخزينها على قرص public مما قد يعرّض بيانات حساسة إذا لم تُحمى الروابط.',
                'status' => 'fixed',
                'recommendation' => 'استخدام قرص خاص (private) للـ CV أو تقييد الوصول بروابط موقّتة.',
            ],
            [
                'type' => 'Missing Security Headers',
                'risk' => 'Medium',
                'file' => 'app/Http/Middleware',
                'description' => 'لا يوجد ميدل وير يضيف Content-Security-Policy و X-Frame-Options و X-Content-Type-Options و Strict-Transport-Security.',
                'status' => 'fixed',
                'recommendation' => 'إضافة ميدل وير SecurityHeaders يضيف هذه الترويسات على الأقل للواجهات العامة.',
            ],
            [
                'type' => 'XSS Hardening (video_url)',
                'risk' => 'Low',
                'file' => 'resources/js/pages/Lesson.vue',
                'description' => 'حقل video_url يمكن أن يُستخدم لتمرير رابط iframe غير آمن إذا لم يتم التحقق منه في الخلفية.',
                'status' => 'fixed',
                'recommendation' => 'تقييد video_url إلى روابط https صالحة ومن دومينات موثوقة فقط في قواعد التحقق.',
            ],
            [
                'type' => 'Stored XSS (محتوى الدرس)',
                'risk' => 'Medium',
                'file' => 'resources/js/pages/Lesson.vue',
                'description' => 'محتوى الدرس (content) يُعرض بـ v-html دون تنقية مما يسمح بتنفيذ سكربت إذا أدخل المدرب HTML خبيث.',
                'status' => 'fixed',
                'recommendation' => 'تنقية HTML على الخادم (HtmlSanitizer) عند الحفظ وعند الإرسال للواجهة، مع السماح بوسوم تنسيق آمنة فقط.',
            ],
        ];

        $protections = $this->service->detectProtections();

        $score = $this->service->calculateSecurityScore($vulnerabilities, $protections);
        $logsSummary = $this->service->summarizeLogs();

        return view('admin.cyber.index', [
            'score' => $score,
            'vulnerabilities' => $vulnerabilities,
            'protections' => $protections,
            'recentLogs' => $logsSummary['recent_logs'],
            'topIps' => $logsSummary['top_ips'],
            'totalLogs' => $logsSummary['total_logs'],
            'recentHighRisk' => $logsSummary['recent_high_risk'],
        ]);
    }
}
