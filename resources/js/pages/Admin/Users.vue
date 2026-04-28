<script setup lang="ts">
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    BookText,
    CreditCard,
    FolderOpen,
    GraduationCap,
    LayoutDashboard,
    Newspaper,
    Trash2,
    UserRound,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { Card } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'لوحة التحكم', href: dashboard() },
    { title: 'لوحة المدير', href: '/admin/dashboard' },
    { title: 'المستخدمين', href: '/admin/users' },
];

const props = withDefaults(
    defineProps<{
        users?: Array<{
            id: number;
            name: string;
            email: string;
            role: string;
            created_at?: string | null;
        }>;
        roleOptions?: Array<{
            value: string;
            label: string;
        }>;
    }>(),
    {
        users: () => [],
        roleOptions: () => [],
    }
);

const page = usePage();
const flash = computed(() => (page.props.flash as { success?: string | null; error?: string | null }) ?? {});

const authUserId = computed(() => {
    const auth = page.props.auth as { user?: { id: number } } | undefined;

    return auth?.user?.id ?? null;
});

const userForm = useForm({
    name: '',
    email: '',
    password: '',
    role: 'student',
});

const roleLabels: Record<string, string> = {
    admin: 'إدارة',
    admin_staff: 'موظف تسجيل',
    academic_supervisor: 'مشرف أكاديمي',
    accountant: 'محاسب',
    teacher: 'أكاديمي',
    student: 'طالب',
};

const sidebarItems = [
    { title: 'لوحة التحكم', href: '/admin/dashboard', icon: LayoutDashboard, active: false },
    { title: 'المستخدمين', href: '/admin/users', icon: UserRound, active: true },
    { title: 'الطلاب', href: '/admin/students', icon: GraduationCap, active: false },
    { title: 'الدورات', href: '/admin/courses', icon: BookOpen, active: false },
    { title: 'البرامج', href: '/admin/programs', icon: FolderOpen, active: false },
    { title: 'الكتب الرقمية', href: '/admin/digital-books', icon: BookText, active: false },
    { title: 'المجلة الاعلامية', href: '/admin/media-journal', icon: Newspaper, active: false },
    { title: 'المدفوعات', href: '/admin/payments', icon: CreditCard, active: false },
    { title: 'التقارير', href: null, icon: BarChart3, active: false },
];

function submitUser() {
    userForm.post('/admin/users', {
        preserveScroll: true,
        onSuccess: () => userForm.reset('name', 'email', 'password'),
    });
}

function deleteUser(id: number, name: string) {
    if (! confirm(`حذف المستخدم «${name}» نهائيًا؟ لا يمكن التراجع عن ذلك.`)) {
        return;
    }
    router.delete(`/admin/users/${id}`, { preserveScroll: true });
}
</script>

<template>
    <Head title="المستخدمين - لوحة المدير" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-full bg-slate-100/70 dark:bg-slate-950">
            <div class="flex w-full">
                <aside class="hidden w-60 shrink-0 self-start border-e border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-[#2C2C2E] lg:block">
                    <div class="sticky top-20 overflow-y-auto">
                        <div class="border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Admin</p>
                            <p class="mt-0.5 text-xs text-slate-500">مدير النظام</p>
                        </div>

                        <nav class="px-3 py-4">
                            <ul class="space-y-1">
                                <li v-for="item in sidebarItems" :key="item.title">
                                    <Link
                                        v-if="item.href"
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

                                    <button
                                        v-else
                                        type="button"
                                        class="flex w-full cursor-default items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-400"
                                    >
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400 dark:bg-slate-800">
                                            <component :is="item.icon" class="h-4 w-4" />
                                        </span>
                                        <span class="leading-tight">{{ item.title }}</span>
                                        <span class="me-auto rounded-full bg-slate-100 px-2 py-0.5 text-[10px] text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                            قريباً
                                        </span>
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </aside>

                <main class="min-w-0 flex-1">
                    <div class="w-full space-y-6 px-6 py-6">
                        <div
                            v-if="flash.success"
                            class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200"
                        >
                            {{ flash.success }}
                        </div>
                        <div
                            v-if="flash.error"
                            class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-200"
                        >
                            {{ flash.error }}
                        </div>

                        <div class="rounded-lg border border-slate-200 bg-white px-6 py-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">المستخدمين</h1>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                جميع المستخدمين في النظام مع إمكانية إضافة مستخدم جديد.
                            </p>
                        </div>

                        <Card class="border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">إضافة مستخدم جديد</h2>

                            <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submitUser">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">الاسم</label>
                                    <input v-model="userForm.name" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-violet-500" />
                                    <p v-if="userForm.errors.name" class="mt-1 text-xs text-rose-600">{{ userForm.errors.name }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">البريد الإلكتروني</label>
                                    <input v-model="userForm.email" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-violet-500" />
                                    <p v-if="userForm.errors.email" class="mt-1 text-xs text-rose-600">{{ userForm.errors.email }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">كلمة المرور</label>
                                    <input v-model="userForm.password" type="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-violet-500" />
                                    <p v-if="userForm.errors.password" class="mt-1 text-xs text-rose-600">{{ userForm.errors.password }}</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">الدور</label>
                                    <select v-model="userForm.role" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none transition focus:border-violet-500">
                                        <option
                                            v-for="option in props.roleOptions"
                                            :key="option.value"
                                            :value="option.value"
                                        >
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <p v-if="userForm.errors.role" class="mt-1 text-xs text-rose-600">{{ userForm.errors.role }}</p>
                                </div>
                                <div class="md:col-span-2 flex justify-end">
                                    <button
                                        type="submit"
                                        :disabled="userForm.processing"
                                        class="inline-flex items-center rounded-xl bg-[#ed9134] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#d67d2a] disabled:opacity-70"
                                    >
                                        إضافة المستخدم
                                    </button>
                                </div>
                            </form>
                        </Card>

                        <Card class="overflow-hidden border-slate-200 bg-white p-0 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div v-if="props.users.length === 0" class="p-8 text-center text-sm text-slate-500">
                                لا يوجد مستخدمون حالياً.
                            </div>
                            <div v-else class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                                    <thead class="bg-slate-50 dark:bg-slate-900/60">
                                        <tr>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">الاسم</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">البريد الإلكتروني</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">الدور</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">تاريخ الإضافة</th>
                                            <th class="px-4 py-3 text-right font-semibold text-slate-700 dark:text-slate-300">إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        <tr v-for="user in props.users" :key="user.id">
                                            <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">{{ user.name }}</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ user.email }}</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ roleLabels[user.role] || user.role }}</td>
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ user.created_at || '—' }}</td>
                                            <td class="px-4 py-3">
                                                <button
                                                    v-if="authUserId !== user.id"
                                                    type="button"
                                                    class="inline-flex items-center gap-1 rounded-md border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100 dark:border-rose-900 dark:bg-rose-950/50 dark:text-rose-300 dark:hover:bg-rose-950"
                                                    @click="deleteUser(user.id, user.name)"
                                                >
                                                    <Trash2 class="h-3.5 w-3.5" />
                                                    حذف
                                                </button>
                                                <span v-else class="text-xs text-slate-400">—</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </Card>
                    </div>
                </main>
            </div>
        </div>
    </AppLayout>
</template>
