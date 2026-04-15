<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Calendar,
    LayoutDashboard,
    MessageSquare,
    Mic,
    MicOff,
    MonitorUp,
    PhoneOff,
    Radio,
    Receipt,
    User,
    Video,
    VideoOff,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

const page = usePage();
const userName = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مستخدم';

const navItems = [
    { title: 'لوحة التحكم', href: dashboard(), icon: LayoutDashboard },
    { title: 'دوراتي', href: '/student/courses', icon: BookOpen },
    { title: 'المحاضرات المسجلة', href: '#', icon: Video },
    { title: 'الواجبات والمشاريع', href: '/student/assignments', icon: MessageSquare },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions', icon: Calendar, active: true },
    { title: 'الاختبارات', href: '#', icon: MessageSquare },
    { title: 'الحضور والغياب', href: '#', icon: Calendar },
    { title: 'المدفوعات والفواتير', href: '#', icon: Receipt },
    { title: 'الملف الشخصي', href: '#', icon: User },
    { title: 'الرسائل والاشعارات', href: '#', icon: MessageSquare },
];

const props = defineProps<{
    session: {
        id: number;
        course_title: string;
        topic: string;
        date_human: string;
        time_range: string;
        status: 'upcoming' | 'live' | 'completed';
        instructor: string;
        platform: string;
        description: string;
    };
    userName: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions' },
    { title: 'غرفة الجلسة', href: '#' },
];
</script>

<template>
    <Head :title="`غرفة الجلسة – ${session.topic}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full w-full bg-[#0B0B0D] text-white">
            <div class="flex w-full">
                <!-- سايدبار على اليمين (نفس روح الداشبورد لكن داكنة) -->
                <aside class="hidden w-60 shrink-0 self-start border-e border-slate-800 bg-[#151518] lg:block">
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-800 px-5 py-5">
                            <p class="text-sm font-bold text-white">{{ userName }}</p>
                            <p class="mt-0.5 text-xs text-slate-400">طالب</p>
                        </div>
                            <nav class="px-3 py-4">
                            <ul class="space-y-1">
                                <li v-for="(item, index) in navItems" :key="index">
                                    <Link
                                        :href="item.href"
                                        class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150"
                                        :class="item.active
                                            ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]'
                                            : 'text-slate-300 hover:bg-slate-800 hover:text-white'"
                                    >
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                            :class="item.active ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-800 text-slate-400'"
                                        >
                                            <component :is="item.icon" class="h-4 w-4" />
                                        </span>
                                        <span class="leading-tight">{{ item.title }}</span>
                                    </Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </aside>

                <!-- محتوى الجلسة -->
                <main class="min-w-0 flex-1">
                    <div class="w-full px-4 py-4 sm:px-6 sm:py-6 space-y-4">
                        <!-- شريط علوي للجلسة -->
                        <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                            <div>
                                <div class="flex items-center gap-2 text-xs text-slate-400">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-800 px-3 py-1">
                                        <Calendar class="h-3.5 w-3.5 text-slate-400" />
                                        {{ session.date_human }} • {{ session.time_range }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-800 px-3 py-1">
                                        <User class="h-3.5 w-3.5 text-slate-400" />
                                        الدكتور: {{ session.instructor }}
                                    </span>
                                </div>
                                <h1 class="mt-2 text-lg font-bold sm:text-xl">
                                    {{ session.topic }}
                                </h1>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ session.course_title }} • المنصة: {{ session.platform }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                                    :class="session.status === 'live'
                                        ? 'bg-red-500/10 text-red-400'
                                        : session.status === 'upcoming'
                                            ? 'bg-amber-500/10 text-amber-300'
                                            : 'bg-slate-600/40 text-slate-200'"
                                >
                                    <span
                                        class="h-2 w-2 rounded-full"
                                        :class="session.status === 'live'
                                            ? 'bg-red-500 animate-pulse'
                                            : session.status === 'upcoming'
                                                ? 'bg-amber-400'
                                                : 'bg-slate-400'"
                                    />
                                    {{ session.status === 'live' ? 'مباشر الآن' : session.status === 'upcoming' ? 'تبدأ قريباً' : 'انتهت' }}
                                </span>
                            </div>
                        </div>

                        <!-- تخطيط: فيديو + شات -->
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-5">
                            <!-- منطقة الفيديو -->
                            <div class="lg:col-span-2">
                                <Card class="flex h-[380px] flex-col overflow-hidden border-0 bg-gradient-to-b from-slate-900 to-black p-0 sm:h-[460px]">
                                    <!-- شريط علوي للفيديو -->
                                    <div class="flex items-center justify-between border-b border-slate-800 px-4 py-3">
                                        <div class="flex items-center gap-2 text-xs text-slate-300">
                                            <Radio class="h-4 w-4 text-red-500" />
                                            <span>البث المباشر (فيديو + صوت)</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-400">
                                            <span class="hidden sm:inline">مشاركة شاشة الدكتور مفعّلة</span>
                                            <MonitorUp class="h-4 w-4 text-slate-400" />
                                        </div>
                                    </div>

                                    <!-- شاشة الفيديو (Placeholder احترافي) -->
                                    <div class="relative flex-1">
                                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_#222_0,_#050509_60%)] opacity-80" />
                                        <div class="relative flex h-full flex-col items-center justify-center gap-4 px-4 text-center">
                                            <div class="inline-flex items-center gap-2 rounded-full bg-slate-900/70 px-4 py-1 text-[11px] text-slate-200">
                                                <MonitorUp class="h-3.5 w-3.5 text-[#ed9134]" />
                                                عرض تقديمي مباشر من شاشة الدكتور
                                            </div>
                                            <p class="max-w-md text-xs text-slate-300 sm:text-sm">
                                                هنا تظهر نافذة البث المباشر (الكاميرا / مشاركة الشاشة) مع صوت الدكتور والشرح المباشر
                                                تمامًا مثل زووم أو جوجل ميت.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- شريط التحكم -->
                                    <div class="flex items-center justify-between border-t border-slate-800 bg-slate-950/80 px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-slate-100 hover:bg-slate-700"
                                            >
                                                <Mic class="h-4 w-4" />
                                            </button>
                                            <button
                                                type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-slate-100 hover:bg-slate-700"
                                            >
                                                <Video class="h-4 w-4" />
                                            </button>
                                            <button
                                                type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-slate-100 hover:bg-slate-700"
                                            >
                                                <MonitorUp class="h-4 w-4" />
                                            </button>
                                        </div>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-red-700"
                                        >
                                            <PhoneOff class="h-3.5 w-3.5" />
                                            إنهاء الجلسة
                                        </button>
                                    </div>
                                </Card>

                                <!-- تفاصيل تحت الفيديو -->
                                <div class="mt-4 space-y-2 text-xs text-slate-300">
                                    <p class="font-semibold text-slate-200">وصف الجلسة</p>
                                    <p class="text-slate-400">
                                        {{ session.description }}
                                    </p>
                                </div>
                            </div>

                            <!-- شات الغرفة -->
                            <div class="flex h-full flex-col gap-3">
                                <Card class="flex h-full flex-col overflow-hidden border-0 bg-[#151518] p-0">
                                    <div class="flex items-center justify-between border-b border-slate-800 px-4 py-3">
                                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-100">
                                            <MessageSquare class="h-4 w-4 text-[#ed9134]" />
                                            غرفة الدردشة
                                        </div>
                                        <span class="rounded-full bg-slate-800 px-2 py-0.5 text-[11px] text-slate-300">
                                            يمكنك طرح الأسئلة للدكتور مباشرة
                                        </span>
                                    </div>

                                    <!-- الرسائل -->
                                    <div class="flex-1 space-y-3 overflow-y-auto px-4 py-3 text-xs">
                                        <div class="space-y-1 rounded-xl bg-slate-900/60 px-3 py-2">
                                            <p class="text-[11px] text-slate-400">
                                                <span class="font-semibold text-slate-100">د. سارة</span> • منذ دقيقة
                                            </p>
                                            <p class="text-slate-100">
                                                لو في أي استفسار عن مثال الشبكات العصبية، اسألوا في الشات وأنا هجاوب لايف.
                                            </p>
                                        </div>

                                        <div class="space-y-1 rounded-xl bg-slate-800/60 px-3 py-2">
                                            <p class="text-[11px] text-slate-400">
                                                <span class="font-semibold text-slate-100">أحمد (أنت)</span> • الآن
                                            </p>
                                            <p class="text-slate-100">
                                                هل هنوفر كود المثال بعد الجلسة عشان نراجعه؟
                                            </p>
                                        </div>
                                    </div>

                                    <!-- إدخال رسالة -->
                                    <div class="border-t border-slate-800 bg-slate-950/80 px-3 py-3">
                                        <form
                                            class="flex items-center gap-2"
                                            @submit.prevent
                                        >
                                            <input
                                                type="text"
                                                placeholder="اكتب سؤالك هنا واسأل الدكتور مباشرة..."
                                                class="h-9 flex-1 rounded-full border border-slate-700 bg-slate-900 px-3 text-xs text-slate-100 placeholder:text-slate-500 focus:border-[#ed9134] focus:outline-none"
                                            />
                                            <button
                                                type="submit"
                                                class="inline-flex h-9 items-center justify-center rounded-full bg-[#ed9134] px-4 text-xs font-semibold text-white hover:bg-[#d67d2a]"
                                            >
                                                إرسال
                                            </button>
                                        </form>
                                    </div>
                                </Card>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>

