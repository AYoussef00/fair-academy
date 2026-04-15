<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    FileText,
    IdCard,
    LayoutDashboard,
    Mail,
    MessageSquare,
    Phone,
    Receipt,
    UploadCloud,
    User,
    UserCircle2,
    Video,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

const page = usePage();
const authUserName = (page.props.auth as { user?: { name?: string } })?.user?.name ?? 'مستخدم';
const flash = page.props.flash as { success?: string | null };

const navItems = [
    { title: 'لوحة التحكم', href: dashboard(), icon: LayoutDashboard },
    { title: 'دوراتي', href: '/student/courses', icon: BookOpen },
    { title: 'المحاضرات المسجلة', href: '#', icon: Video },
    { title: 'الواجبات والمشاريع', href: '/student/assignments', icon: FileText },
    { title: 'الجلسات المباشرة', href: '/student/live-sessions', icon: Calendar },
    { title: 'الاختبارات', href: '/student/exams', icon: FileText },
    { title: 'الحضور والغياب', href: '#', icon: Calendar },
    { title: 'المدفوعات والفواتير', href: '/student/payments', icon: Receipt },
    { title: 'الملف الشخصي', href: '/student/profile', icon: User, active: true },
    { title: 'الرسائل والاشعارات', href: '#', icon: MessageSquare },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'الملف الشخصي', href: '/student/profile' },
];

const props = defineProps<{
    profile: {
        name: string | null;
        email: string | null;
        phone: string | null;
        national_id: string | null;
        program_title: string | null;
        avatar_url: string | null;
        cv_url: string | null;
    };
}>();

function uploadCv(event: Event) {
    const target = event.target as HTMLInputElement | null;
    if (!target?.files?.length) return;

    const file = target.files[0];
    const formData = new FormData();
    formData.append('cv', file);

    router.post('/student/profile/cv', formData, {
        forceFormData: true,
        preserveScroll: true,
    });

    target.value = '';
}
</script>

<template>
    <Head title="الملف الشخصي – طالب" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full w-full bg-[#F8F9FA] dark:bg-[#1C1C1E]">
            <div class="flex w-full">
                <!-- سايدبار على اليمين -->
                <aside
                    class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block"
                >
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ authUserName }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">طالب</p>
                        </div>
                        <nav class="px-3 py-4">
                            <ul class="space-y-1">
                                <li v-for="(item, index) in navItems" :key="index">
                                    <Link
                                        :href="item.href"
                                        class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-150"
                                        :class="item.active
                                            ? 'bg-[#ed9134]/10 font-semibold text-[#ed9134]'
                                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100'"
                                    >
                                        <span
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                            :class="item.active ? 'bg-[#ed9134]/15 text-[#ed9134]' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400'"
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

                <!-- المحتوى الرئيسي -->
                <main class="min-w-0 flex-1">
                    <div class="w-full px-6 py-6 space-y-6">
                        <!-- فلاش نجاح -->
                        <div
                            v-if="flash?.success"
                            class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/50 dark:bg-emerald-900/30 dark:text-emerald-100"
                        >
                            {{ flash.success }}
                        </div>

                        <!-- الهيدر -->
                        <div class="flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                            <div>
                                <h1 class="text-xl font-bold text-slate-900 dark:text-white">👤 الملف الشخصي</h1>
                                <p class="mt-1 text-sm text-slate-500">
                                    هنا يمكنك مشاهدة بياناتك الشخصية، تعديلها، ورفع سيرتك الذاتية.
                                </p>
                            </div>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700"
                            >
                                <User class="h-3.5 w-3.5" />
                                تعديل البيانات
                            </button>
                        </div>

                        <!-- الصورة + البيانات -->
                        <div class="grid grid-cols-1 gap-5 lg:grid-cols-[minmax(0,1.4fr),minmax(0,1.1fr)]">
                            <!-- بيانات أساسية -->
                            <Card class="border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="relative h-20 w-20 overflow-hidden rounded-full border border-slate-200 bg-slate-100 dark:border-slate-600 dark:bg-slate-700">
                                            <img
                                                v-if="profile.avatar_url"
                                                :src="profile.avatar_url"
                                                :alt="profile.name ?? 'صورة الطالب'"
                                                class="h-full w-full object-cover"
                                            />
                                            <UserCircle2
                                                v-else
                                                class="h-full w-full text-slate-400"
                                            />
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white">
                                                {{ profile.name || 'الاسم غير متوفر' }}
                                            </p>
                                            <p class="mt-0.5 text-xs text-slate-500">
                                                طالب في {{ profile.program_title || 'برنامج غير محدد' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="space-y-1">
                                        <p class="flex items-center gap-1 text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                            <User class="h-3.5 w-3.5" />
                                            الاسم الكامل
                                        </p>
                                        <p class="text-sm text-slate-800 dark:text-slate-100">
                                            {{ profile.name || 'لم يتم إدخال الاسم بعد' }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="flex items-center gap-1 text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                            <Phone class="h-3.5 w-3.5" />
                                            رقم الجوال
                                        </p>
                                        <p class="text-sm text-slate-800 dark:text-slate-100">
                                            {{ profile.phone || 'لم يتم إدخال رقم الجوال بعد' }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="flex items-center gap-1 text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                            <Mail class="h-3.5 w-3.5" />
                                            البريد الإلكتروني
                                        </p>
                                        <p class="text-sm text-slate-800 dark:text-slate-100">
                                            {{ profile.email || 'لم يتم إدخال البريد بعد' }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="flex items-center gap-1 text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                            <IdCard class="h-3.5 w-3.5" />
                                            رقم الهوية / الإقامة
                                        </p>
                                        <p class="text-sm text-slate-800 dark:text-slate-100">
                                            {{ profile.national_id || 'لم يتم إدخال رقم الهوية بعد' }}
                                        </p>
                                    </div>
                                </div>
                            </Card>

                            <!-- البرنامج + CV -->
                            <div class="space-y-4">
                                <Card class="border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                    <h2 class="mb-2 text-sm font-bold text-slate-900 dark:text-white">
                                        🎓 البرنامج المسجل فيه
                                    </h2>
                                    <p class="text-sm text-slate-800 dark:text-slate-100">
                                        {{ profile.program_title || 'دبلوم التقنية والذكاء الاصطناعي' }}
                                    </p>
                                    <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">
                                        هذا هو البرنامج الرئيسي الذي تم تسجيلك فيه كطالب.
                                    </p>
                                </Card>

                                <Card class="border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                                    <div class="flex items-center justify-between gap-2">
                                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">
                                            📄 السيرة الذاتية (CV)
                                        </h2>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        يمكنك رفع ملف السيرة الذاتية بصيغة PDF أو Word ليطلع عليه فريق الأكاديمية والمدربون.
                                    </p>

                                    <div class="mt-3 flex flex-col gap-2">
                                        <label
                                            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-[#ed9134] px-4 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#d67d2a]"
                                        >
                                            <UploadCloud class="h-4 w-4" />
                                            رفع السيرة الذاتية (PDF / DOC / DOCX)
                                            <input
                                                type="file"
                                                class="hidden"
                                                accept=".pdf,.doc,.docx"
                                                @change="uploadCv"
                                            />
                                        </label>

                                        <p class="text-[11px] text-slate-400">
                                            الحد الأقصى للحجم 10 ميجابايت. في حال قمت برفع ملف جديد فسيتم اعتماد آخر نسخة فقط.
                                        </p>
                                        <a
                                            v-if="profile.cv_url"
                                            :href="profile.cv_url"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="mt-2 inline-flex items-center gap-2 text-sm font-medium text-[#ed9134] hover:underline"
                                        >
                                            <FileText class="h-4 w-4" />
                                            تحميل السيرة الذاتية الحالية
                                        </a>
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

