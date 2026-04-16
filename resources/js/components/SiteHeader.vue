<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Globe, Menu, Search, ShoppingCart } from 'lucide-vue-next';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import {
    courses,
    dashboard,
    home,
    login,
    register,
} from '@/routes';
import type { User } from '@/types';

const page = usePage();
const auth = computed(() => page.props.auth as { user?: User } | undefined);
const authUser = computed(() => auth.value?.user);
const cartCount = computed(() => (page.props.cartCount as number) ?? 0);

const logoHref = computed(() => home());
</script>

<template>
    <header
        class="sticky top-0 z-50 border-b border-slate-200/90 bg-white/95 backdrop-blur"
    >
        <div class="mx-auto flex h-14 max-w-7xl items-center gap-2 px-3 sm:h-16 sm:gap-4 sm:px-6">
            <!-- Logo -->
            <Link
                :href="logoHref"
                class="flex shrink-0 items-center"
            >
                <img
                    src="/asset/2e10fbcb-7501-4ed6-963f-7f2eb436ed1b.jpeg"
                    alt="أكاديمية فايرير للتدريب والتعليم"
                    class="h-10 w-auto object-contain sm:h-12 md:h-16"
                />
            </Link>

            <!-- Primary nav: Explore, Courses -->
            <nav class="hidden shrink-0 items-center gap-1 lg:flex">
                <Link
                    :href="home()"
                    class="rounded px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                >
                    البرامج
                </Link>
                <Link
                    :href="courses()"
                    class="rounded px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                >
                    الدورات
                </Link>
                <Link
                    href="/scientific-journal"
                    class="rounded px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                >
                    المجلة العلمية
                </Link>
            </nav>

            <!-- Center: Search (pill) -->
            <div class="relative hidden min-w-0 max-w-xl flex-1 xl:block">
                <Search
                    class="pointer-events-none absolute start-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                />
                <input
                    type="search"
                    placeholder="ابحث عن أي شيء"
                    class="h-10 w-full rounded-full border border-slate-200 bg-slate-50 ps-11 pe-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-violet-500"
                />
            </div>

            <!-- End: Cart, Auth -->
            <div class="ms-auto flex shrink-0 items-center gap-1 sm:gap-2">
                <Link
                    v-if="authUser"
                    href="/cart"
                    class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50"
                >
                    <ShoppingCart class="h-5 w-5" />
                    <span
                        v-if="cartCount > 0"
                        class="absolute -top-0.5 -end-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-violet-600 px-1 text-[10px] font-bold text-white"
                    >
                        {{ cartCount > 99 ? '99+' : cartCount }}
                    </span>
                    <span class="sr-only">السلة ({{ cartCount }})</span>
                </Link>

                <template v-if="authUser">
                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative h-10 w-10 rounded-full p-0"
                            >
                                <Avatar class="h-8 w-8">
                                    <AvatarImage
                                        v-if="authUser.avatar"
                                        :src="authUser.avatar"
                                        :alt="authUser.name"
                                    />
                                    <AvatarFallback
                                        class="rounded-full bg-slate-200 text-sm font-semibold text-slate-700"
                                    >
                                        {{ getInitials(authUser.name) }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <UserMenuContent :user="authUser" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                </template>
                <template v-else>
                    <Link :href="login()" class="hidden md:block">
                        <Button
                            variant="outline"
                            class="h-9 rounded-full border-[#ed9134] bg-transparent px-4 text-sm font-medium text-[#ed9134] hover:bg-[#fff4e8]"
                        >
                            تسجيل دخول
                        </Button>
                    </Link>
                    <Link :href="register()" class="hidden md:block">
                        <Button
                            class="h-9 rounded-full bg-[#ed9134] px-4 text-sm font-semibold text-white hover:bg-[#d67d2a]"
                        >
                            اشترك الآن
                        </Button>
                    </Link>
                </template>

                <Button
                    variant="outline"
                    size="icon"
                    class="hidden h-9 w-9 shrink-0 rounded-full border-slate-200 bg-white lg:inline-flex"
                >
                    <Globe class="h-5 w-5 text-slate-600" />
                    <span class="sr-only">اللغة</span>
                </Button>

                <!-- Mobile menu -->
                <Sheet>
                    <SheetTrigger :as-child="true">
                        <Button
                            variant="ghost"
                            size="icon"
                            class="h-9 w-9 lg:hidden"
                        >
                            <Menu class="h-5 w-5" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="right" class="w-[88vw] max-w-[320px] p-5 sm:p-6">
                        <SheetHeader class="text-right">
                            <SheetTitle class="sr-only">القائمة</SheetTitle>
                            <Link :href="logoHref" class="inline-flex">
                                <img
                                    src="/asset/2e10fbcb-7501-4ed6-963f-7f2eb436ed1b.jpeg"
                                    alt="أكاديمية فايرير للتدريب والتعليم"
                                    class="h-14 w-auto object-contain"
                                />
                            </Link>
                        </SheetHeader>
                        <div class="relative mt-6">
                            <Search
                                class="pointer-events-none absolute start-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />
                            <input
                                type="search"
                                placeholder="ابحث عن أي شيء"
                                class="h-10 w-full rounded-full border border-slate-200 bg-slate-50 ps-11 pe-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-violet-500"
                            />
                        </div>
                        <div class="mt-6 flex flex-col gap-2">
                            <Link
                                :href="home()"
                                class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                            >
                                البرامج
                            </Link>
                            <Link
                                :href="courses()"
                                class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                            >
                                الدورات
                            </Link>
                            <Link
                                href="/scientific-journal"
                                class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                            >
                                المجلة العلمية
                            </Link>
                            <Link
                                v-if="authUser"
                                :href="dashboard()"
                                class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                            >
                                لوحة التحكم
                            </Link>
                            <Link
                                v-if="authUser"
                                href="/cart"
                                class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
                            >
                                السلة {{ cartCount > 0 ? `(${cartCount})` : '' }}
                            </Link>
                            <template v-if="!authUser">
                                <div class="my-2 border-t border-slate-200" />
                                <Link :href="login()" class="rounded-lg px-3 py-2.5 text-sm font-medium text-[#ed9134]">
                                    تسجيل الدخول
                                </Link>
                                <Link :href="register()" class="rounded-lg bg-violet-600 px-3 py-2.5 text-center text-sm font-semibold text-white">
                                    إنشاء حساب
                                </Link>
                            </template>
                        </div>
                    </SheetContent>
                </Sheet>
            </div>
        </div>
    </header>
</template>
