<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { BookOpen } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { home } from '@/routes';

defineProps<{
    canRegister?: boolean;
}>();
</script>

<template>
    <Head title="إنشاء حساب – أكاديمية فايرير للتدريب والتعليم" />
    <div class="flex min-h-svh bg-[#fafafa]">
        <!-- Left: Branding panel -->
        <div
            class="relative hidden w-[48%] flex-col overflow-hidden border-e border-slate-200 bg-white p-12 lg:flex"
        >
            <div class="flex flex-1 flex-col items-center justify-center">
                <Link :href="home()" class="inline-block">
                    <img
                        src="/asset/2e10fbcb-7501-4ed6-963f-7f2eb436ed1b.jpeg"
                        alt="أكاديمية فايرير للتدريب والتعليم"
                        class="h-32 w-auto object-contain sm:h-40 md:h-44"
                    />
                </Link>
            </div>
            <div class="relative z-10 max-w-sm pb-8">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#ed9134]/10 text-[#ed9134]">
                    <BookOpen class="h-7 w-7" />
                </div>
                <h2 class="text-3xl font-bold leading-tight text-slate-900">
                    انضم إلى الأكاديمية
                </h2>
                <p class="mt-4 text-lg text-slate-600">
                    أنشئ حسابك وابدأ رحلة التعلّم مع آلاف الطلاب. احصل على وصولٍ إلى الدورات والشهادات.
                </p>
            </div>
        </div>

        <!-- Right: Form panel -->
        <div class="flex flex-1 flex-col justify-center px-6 py-12 sm:px-12 lg:px-16">
            <div class="mx-auto w-full max-w-[400px]">
                <!-- Logo for mobile -->
                <Link :href="home()" class="mb-10 inline-block lg:hidden">
                    <img
                        src="/asset/2e10fbcb-7501-4ed6-963f-7f2eb436ed1b.jpeg"
                        alt="أكاديمية فايرير للتدريب والتعليم"
                        class="h-12 w-auto object-contain"
                    />
                </Link>

                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-slate-900">إنشاء حساب</h1>
                    <p class="mt-1 text-slate-600">
                        أدخل بياناتك لإنشاء حساب جديد
                    </p>
                </div>

                <Form
                    v-bind="store.form()"
                    :reset-on-success="['password', 'password_confirmation']"
                    v-slot="{ errors, processing }"
                    class="flex flex-col gap-5"
                >
                    <div class="grid gap-2">
                        <Label for="name" class="text-sm font-medium text-slate-700">
                            الاسم الكامل
                        </Label>
                        <Input
                            id="name"
                            type="text"
                            name="name"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="name"
                            placeholder="أدخل اسمك"
                            class="h-11 rounded-lg border-slate-300 bg-white px-4 text-slate-900 placeholder:text-slate-400 focus-visible:ring-[#ed9134]"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email" class="text-sm font-medium text-slate-700">
                            البريد الإلكتروني
                        </Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            required
                            :tabindex="2"
                            autocomplete="email"
                            placeholder="you@example.com"
                            class="h-11 rounded-lg border-slate-300 bg-white px-4 text-slate-900 placeholder:text-slate-400 focus-visible:ring-[#ed9134]"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password" class="text-sm font-medium text-slate-700">
                            كلمة المرور
                        </Label>
                        <Input
                            id="password"
                            type="password"
                            name="password"
                            required
                            :tabindex="3"
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="h-11 rounded-lg border-slate-300 bg-white px-4 text-slate-900 placeholder:text-slate-400 focus-visible:ring-[#ed9134]"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation" class="text-sm font-medium text-slate-700">
                            تأكيد كلمة المرور
                        </Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            :tabindex="4"
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="h-11 rounded-lg border-slate-300 bg-white px-4 text-slate-900 placeholder:text-slate-400 focus-visible:ring-[#ed9134]"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <Button
                        type="submit"
                        class="h-12 w-full rounded-xl bg-[#ed9134] text-base font-semibold text-white shadow-lg shadow-[#ed9134]/25 transition hover:bg-[#d67d2a] hover:shadow-[#ed9134]/30"
                        :tabindex="5"
                        :disabled="processing"
                        data-test="register-user-button"
                    >
                        <Spinner v-if="processing" class="me-2" />
                        إنشاء حساب
                    </Button>
                </Form>

                <p class="mt-8 text-center text-sm text-slate-600">
                    لديك حساب بالفعل؟
                    <Link
                        :href="login()"
                        class="font-semibold text-[#ed9134] hover:text-[#d67d2a]"
                        :tabindex="6"
                    >
                        تسجيل الدخول
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>
