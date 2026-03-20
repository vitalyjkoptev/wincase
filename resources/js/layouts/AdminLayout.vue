<script setup lang="ts">
// =====================================================
// FILE: src/layouts/AdminLayout.vue
// Admin Layout — Sidebar + Topbar + Content
// =====================================================

import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import {
  HomeIcon, UserGroupIcon, FolderIcon, BriefcaseIcon,
  CurrencyDollarIcon, ClipboardDocumentListIcon,
  CalendarIcon, Cog6ToothIcon, ArrowRightOnRectangleIcon,
  Bars3Icon, XMarkIcon, BellIcon, UserCircleIcon,
} from '@heroicons/vue/24/outline'

const auth = useAuthStore()
const router = useRouter()
const route = useRoute()
const sidebarOpen = ref(false)

const user = computed(() => auth.user)

interface NavItem {
  name: string
  path: string
  icon: any
  ability: string
}

const navigation: NavItem[] = [
  { name: 'Dashboard', path: '/dashboard', icon: HomeIcon, ability: 'dashboard:read' },
  { name: 'Leads', path: '/leads', icon: UserGroupIcon, ability: 'leads:read' },
  { name: 'Clients', path: '/clients', icon: BriefcaseIcon, ability: 'clients:read' },
  { name: 'Cases', path: '/cases', icon: FolderIcon, ability: 'cases:read' },
  { name: 'POS Terminal', path: '/pos', icon: CurrencyDollarIcon, ability: 'pos:read' },
  { name: 'Tasks', path: '/tasks', icon: ClipboardDocumentListIcon, ability: 'tasks:read' },
  { name: 'Calendar', path: '/calendar', icon: CalendarIcon, ability: 'calendar:read' },
  { name: 'Settings', path: '/settings', icon: Cog6ToothIcon, ability: '*' },
]

const visibleNav = computed(() => {
  const perms = user.value?.permissions ?? []
  if (perms.includes('*')) return navigation
  return navigation.filter(item => perms.includes(item.ability))
})

function isActive(path: string): boolean {
  return route.path.startsWith(path)
}

async function logout() {
  await auth.doLogout()
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile sidebar overlay -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-40 bg-black/50 lg:hidden"
      @click="sidebarOpen = false"
    />

    <!-- Sidebar -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-brand text-white transform transition-transform duration-200 lg:translate-x-0',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <!-- Logo -->
      <div class="flex items-center h-16 px-6 border-b border-brand-light">
        <span class="text-xl font-bold tracking-wide">WinCase</span>
        <span class="ml-2 text-xs text-brand-light/80">CRM v4.0</span>
      </div>

      <!-- Navigation -->
      <nav class="mt-4 px-3 space-y-1">
        <router-link
          v-for="item in visibleNav"
          :key="item.path"
          :to="item.path"
          :class="[
            'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors',
            isActive(item.path)
              ? 'bg-white/15 text-white font-medium'
              : 'text-white/70 hover:bg-white/10 hover:text-white',
          ]"
          @click="sidebarOpen = false"
        >
          <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
          {{ item.name }}
        </router-link>
      </nav>

      <!-- User info at bottom -->
      <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-brand-light">
        <div class="flex items-center gap-3">
          <UserCircleIcon class="w-8 h-8 text-white/60" />
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium truncate">{{ user?.name }}</p>
            <p class="text-xs text-white/50 capitalize">{{ user?.role }}</p>
          </div>
          <button
            class="text-white/40 hover:text-white transition-colors"
            title="Logout"
            @click="logout"
          >
            <ArrowRightOnRectangleIcon class="w-5 h-5" />
          </button>
        </div>
      </div>
    </aside>

    <!-- Main content area -->
    <div class="lg:pl-64">
      <!-- Topbar -->
      <header class="sticky top-0 z-30 flex items-center h-16 bg-white border-b border-gray-200 px-4 lg:px-8">
        <button
          class="lg:hidden p-2 -ml-2 text-gray-500 hover:text-gray-700"
          @click="sidebarOpen = !sidebarOpen"
        >
          <Bars3Icon v-if="!sidebarOpen" class="w-6 h-6" />
          <XMarkIcon v-else class="w-6 h-6" />
        </button>

        <div class="flex-1" />

        <!-- Topbar actions -->
        <div class="flex items-center gap-4">
          <button class="relative p-2 text-gray-400 hover:text-gray-600">
            <BellIcon class="w-6 h-6" />
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full" />
          </button>

          <div class="text-right hidden sm:block">
            <p class="text-sm font-medium text-gray-700">{{ user?.name }}</p>
            <p class="text-xs text-gray-400">{{ user?.email }}</p>
          </div>
        </div>
      </header>

      <!-- Page content -->
      <main class="p-4 lg:p-8">
        <router-view />
      </main>
    </div>
  </div>
</template>

<!--
Аннотация (RU):
AdminLayout.vue — основной layout admin panel.
Sidebar: логотип WinCase CRM v4.0, 8 nav items (фильтруются по RBAC abilities).
Topbar: hamburger menu (mobile), notifications bell, user info.
User section: имя, роль, кнопка logout.
Responsive: mobile sidebar с overlay, lg:translate-x-0.
Brand color #1F3864 (bg-brand).
Файл: src/layouts/AdminLayout.vue
-->
