<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ArrowRight, BookText, FileCheck2, Microscope } from 'lucide-vue-next';
import SiteHeader from '@/components/SiteHeader.vue';

const props = withDefaults(
    defineProps<{
        articles?: Array<{
            id: number;
            title: string;
            researcher_name?: string | null;
            category: string;
            excerpt: string;
            keywords?: string | null;
            content: string;
            pdf_url?: string | null;
            author?: string | null;
            published_at?: string | null;
        }>;
    }>(),
    {
        articles: () => [],
    }
);
</script>

<template>
    <Head title="المجلة العلمية" />

    <div class="min-h-screen bg-slate-50 text-slate-900">
        <SiteHeader />

        <section class="relative overflow-hidden border-b border-slate-200 bg-white">
            <div
                class="absolute inset-0 bg-[radial-gradient(ellipse_75%_55%_at_50%_-20%,rgba(99,102,241,0.14),transparent)]"
            />
            <div class="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-20">
                <p class="inline-flex items-center gap-2 rounded-full bg-violet-100 px-4 py-1.5 text-xs font-semibold text-violet-700">
                    <Microscope class="h-4 w-4" />
                    منصة نشر أكاديمية
                </p>

                <h1 class="mt-5 max-w-3xl text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl md:text-5xl">
                    المجلة العلمية
                </h1>

                <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    مساحة متخصصة لعرض المحتوى العلمي الاحترافي، مصممة لنشر الأبحاث والأوراق المعتمدة بجودة أكاديمية عالية.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
            <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                    المقالات المنشورة
                </h2>
                <span class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-emerald-700">
                    منشور بعد اعتماد الأدمن
                </span>
            </div>

            <div v-if="props.articles.length === 0" class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">
                لا توجد مقالات منشورة حتى الآن.
            </div>

            <div v-else class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <article
                    v-for="article in props.articles"
                    :key="article.id"
                    class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="mb-4 flex items-center justify-between gap-2">
                        <span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700">
                            {{ article.category }}
                        </span>
                        <BookText class="h-4 w-4 text-slate-400" />
                    </div>

                    <h3 class="line-clamp-2 text-lg font-bold leading-snug text-slate-900">
                        {{ article.title }}
                    </h3>

                    <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-slate-600">
                        {{ article.excerpt }}
                    </p>

                    <p v-if="article.keywords" class="mt-3 line-clamp-2 text-xs text-slate-500">
                        الكلمات المفتاحية: {{ article.keywords }}
                    </p>

                    <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 whitespace-nowrap">
                            <FileCheck2 class="h-4 w-4" />
                            ورقة معتمدة
                        </span>
                        <span class="text-xs text-slate-500">{{ article.researcher_name || article.author || 'باحث' }}</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">{{ article.published_at }}</span>
                        <a
                            v-if="article.pdf_url"
                            :href="article.pdf_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1 text-xs font-semibold text-violet-600 transition hover:text-violet-700"
                        >
                            تحميل PDF
                            <ArrowRight class="h-3 w-3" />
                        </a>
                    </div>
                </article>
            </div>

        </section>
    </div>
</template>
