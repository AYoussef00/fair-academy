<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, CreditCard } from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

const props = defineProps<{
    courses: Array<{
        id: number;
        title: string;
        thumbnail: string | null;
        price: number;
    }>;
    subtotal: number;
}>();

const form = useForm({
    card_number: '',
    card_exp_month: '',
    card_exp_year: '',
    card_cvv: '',
    card_name: '',
});

function submit() {
    form.post('/checkout', {
        preserveScroll: true,
        onSuccess: () => {},
    });
}

const totalFormatted = () =>
    props.subtotal > 0 ? `usd ${props.subtotal.toFixed(2)}` : 'مجاني';
</script>

<template>
    <Head title="الدفع – أكاديمية فايرير للتدريب والتعليم" />
    <div class="flex min-h-screen flex-col bg-white">
        <SiteHeader />
        <main class="mx-auto w-full max-w-4xl flex-1 px-4 py-8 sm:px-6">
            <Link
                href="/cart"
                class="mb-6 inline-flex items-center gap-1 text-sm font-medium text-slate-600 hover:text-violet-600"
            >
                <ArrowLeft class="h-4 w-4" />
                العودة إلى السلة
            </Link>

            <h1 class="text-2xl font-bold text-slate-900">إتمام الدفع</h1>
            <p class="mt-1 text-slate-600">
                أدخل بيانات البطاقة لإكمال الشراء والتسجيل في الدورات
            </p>

            <div class="mt-8 grid gap-8 lg:grid-cols-2">
                <!-- بيانات البطاقة -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/30 p-6">
                    <h2 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                        <CreditCard class="h-5 w-5 text-violet-600" />
                        بيانات البطاقة
                    </h2>
                    <form @submit.prevent="submit" class="mt-6 space-y-4">
                        <div>
                            <Label for="card_name" class="text-sm font-medium text-slate-700">
                                اسم صاحب البطاقة
                            </Label>
                            <Input
                                id="card_name"
                                v-model="form.card_name"
                                type="text"
                                required
                                autocomplete="cc-name"
                                placeholder="الاسم كما يظهر على البطاقة"
                                class="mt-1 h-11 rounded-lg border-slate-300 bg-white px-4"
                            />
                            <InputError :message="form.errors.card_name" />
                        </div>
                        <div>
                            <Label for="card_number" class="text-sm font-medium text-slate-700">
                                رقم البطاقة
                            </Label>
                            <Input
                                id="card_number"
                                v-model="form.card_number"
                                type="text"
                                required
                                autocomplete="cc-number"
                                placeholder="1234 5678 9012 3456"
                                maxlength="19"
                                class="mt-1 h-11 rounded-lg border-slate-300 bg-white px-4 font-mono"
                            />
                            <InputError :message="form.errors.card_number" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label for="card_exp_month" class="text-sm font-medium text-slate-700">
                                    شهر الانتهاء
                                </Label>
                                <Input
                                    id="card_exp_month"
                                    v-model="form.card_exp_month"
                                    type="text"
                                    required
                                    placeholder="01"
                                    maxlength="2"
                                    class="mt-1 h-11 rounded-lg border-slate-300 bg-white px-4 font-mono"
                                />
                                <InputError :message="form.errors.card_exp_month" />
                            </div>
                            <div>
                                <Label for="card_exp_year" class="text-sm font-medium text-slate-700">
                                    سنة الانتهاء
                                </Label>
                                <Input
                                    id="card_exp_year"
                                    v-model="form.card_exp_year"
                                    type="text"
                                    required
                                    placeholder="2028"
                                    maxlength="4"
                                    class="mt-1 h-11 rounded-lg border-slate-300 bg-white px-4 font-mono"
                                />
                                <InputError :message="form.errors.card_exp_year" />
                            </div>
                        </div>
                        <div>
                            <Label for="card_cvv" class="text-sm font-medium text-slate-700">
                                رمز الأمان (CVV)
                            </Label>
                            <Input
                                id="card_cvv"
                                v-model="form.card_cvv"
                                type="text"
                                required
                                autocomplete="cc-csc"
                                placeholder="123"
                                maxlength="4"
                                class="mt-1 h-11 w-32 rounded-lg border-slate-300 bg-white px-4 font-mono"
                            />
                            <InputError :message="form.errors.card_cvv" />
                        </div>
                        <Button
                            type="submit"
                            class="mt-4 h-12 w-full rounded-xl bg-violet-600 text-base font-semibold text-white hover:bg-violet-700"
                            :disabled="form.processing"
                        >
                            <Spinner v-if="form.processing" class="me-2 h-4 w-4" />
                            تأكيد الدفع
                        </Button>
                    </form>
                </div>

                <!-- ملخص الطلب -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/30 p-6">
                    <h2 class="text-lg font-bold text-slate-900">ملخص الطلب</h2>
                    <ul class="mt-4 space-y-3">
                        <li
                            v-for="course in courses"
                            :key="course.id"
                            class="flex justify-between text-sm"
                        >
                            <span class="text-slate-700 line-clamp-1">{{ course.title }}</span>
                            <span class="shrink-0 font-semibold text-slate-900">
                                {{ course.price > 0 ? `$${course.price.toFixed(2)}` : 'مجاني' }}
                            </span>
                        </li>
                    </ul>
                    <div class="mt-4 border-t border-slate-200 pt-4">
                        <div class="flex justify-between text-lg font-bold text-slate-900">
                            <span>الإجمالي</span>
                            <span>{{ totalFormatted() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
