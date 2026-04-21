<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { BookOpen, LogOut, Receipt } from 'lucide-vue-next';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/UserInfo.vue';
import { dashboard, logout } from '@/routes';
import type { User } from '@/types';

type Props = {
    user: User;
};

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <Link
            :href="dashboard()"
            class="block rounded-md px-1 py-1.5 text-start text-sm transition hover:bg-slate-50"
            prefetch
        >
            <div class="flex items-center gap-2">
                <UserInfo :user="user" :show-email="true" />
            </div>
        </Link>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full cursor-pointer" href="/my-books" prefetch>
                <BookOpen class="me-2 h-4 w-4" />
                الكتب
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full cursor-pointer" href="/my-invoices" prefetch>
                <Receipt class="me-2 h-4 w-4" />
                الفواتير
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Link
            class="block w-full cursor-pointer"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <LogOut class="me-2 h-4 w-4" />
            تسجيل الخروج
        </Link>
    </DropdownMenuItem>
</template>
