<script setup lang="ts">
// =====================================================
// FILE: src/components/NotificationsPanel.vue
// Bell icon dropdown + real-time notifications
// =====================================================

import { ref, onMounted, onUnmounted, computed } from 'vue'
import api from '@/composables/useApi'
import { useAuthStore } from '@/stores/auth'
import { BellIcon, BellAlertIcon, XMarkIcon } from '@heroicons/vue/24/outline'

interface Notification {
  id: number
  type: string
  title: string
  body: string
  priority: string
  read_at: string | null
  created_at: string
}

const auth = useAuthStore()
const isOpen = ref(false)
const notifications = ref<Notification[]>([])
const unreadCount = ref(0)
const loading = ref(false)

// =====================================================
// LOAD
// =====================================================

async function load() {
  loading.value = true
  try {
    const { data } = await api.get('/notifications', { params: { limit: 20 } })
    notifications.value = data.data?.notifications ?? []
    unreadCount.value = data.data?.unread_count ?? 0
  } catch { /* */ }
  loading.value = false
}

async function fetchUnreadCount() {
  try {
    const { data } = await api.get('/notifications/unread-count')
    unreadCount.value = data.data?.count ?? 0
  } catch { /* */ }
}

async function markAllRead() {
  await api.post('/notifications/read')
  unreadCount.value = 0
  notifications.value = notifications.value.map(n => ({ ...n, read_at: new Date().toISOString() }))
}

async function markRead(id: number) {
  await api.post(`/notifications/${id}/read`)
  const n = notifications.value.find(x => x.id === id)
  if (n && !n.read_at) {
    n.read_at = new Date().toISOString()
    unreadCount.value = Math.max(0, unreadCount.value - 1)
  }
}

// =====================================================
// WEBSOCKET (Reverb — private channel)
// =====================================================

let echoChannel: any = null

function connectWS() {
  // @ts-ignore
  if (typeof window.Echo === 'undefined' || !auth.user) return

  // @ts-ignore
  echoChannel = window.Echo.private(`user.${auth.user.id}`)
    .listen('.notification', (event: any) => {
      notifications.value.unshift({
        id: event.id,
        type: event.type,
        title: event.title,
        body: event.body,
        priority: event.priority,
        read_at: null,
        created_at: event.created_at,
      })
      unreadCount.value++

      // Browser notification
      if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(event.title, { body: event.body, icon: '/favicon.ico' })
      }
    })
}

// =====================================================
// HELPERS
// =====================================================

function typeIcon(type: string): string {
  const icons: Record<string, string> = {
    lead_new: '👤', lead_unassigned: '⚠️', case_status_changed: '📁',
    case_deadline: '⏰', task_assigned: '📋', task_overdue: '🔴',
    document_expiring: '📄', payment_received: '💰', payment_pending: '💳',
    client_message: '💬', system_alert: '🔧', news_published: '📰',
  }
  return icons[type] || '📬'
}

function priorityBorder(p: string): string {
  return { urgent: 'border-l-red-500', high: 'border-l-orange-400', normal: 'border-l-blue-300', low: 'border-l-gray-200' }[p] || 'border-l-gray-200'
}

function timeAgo(ts: string): string {
  const diff = Date.now() - new Date(ts).getTime()
  const mins = Math.floor(diff / 60000)
  if (mins < 1) return 'now'
  if (mins < 60) return `${mins}m`
  const hours = Math.floor(mins / 60)
  if (hours < 24) return `${hours}h`
  return `${Math.floor(hours / 24)}d`
}

// =====================================================
// LIFECYCLE
// =====================================================

onMounted(() => {
  load()
  connectWS()
  // Request browser notification permission
  if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission()
  }
})

onUnmounted(() => {
  if (echoChannel) echoChannel.stopListening('.notification')
})
</script>

<template>
  <div class="relative">
    <!-- Bell button -->
    <button
      @click="isOpen = !isOpen; if(isOpen) load()"
      class="relative p-2 text-gray-400 hover:text-gray-600 transition"
    >
      <BellAlertIcon v-if="unreadCount > 0" class="w-6 h-6 text-brand" />
      <BellIcon v-else class="w-6 h-6" />
      <span
        v-if="unreadCount > 0"
        class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center"
      >
        {{ unreadCount > 99 ? '99+' : unreadCount }}
      </span>
    </button>

    <!-- Dropdown -->
    <div
      v-if="isOpen"
      class="absolute right-0 top-12 w-96 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
    >
      <!-- Header -->
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 text-sm">Notifications</h3>
        <div class="flex items-center gap-3">
          <button
            v-if="unreadCount > 0"
            @click="markAllRead"
            class="text-xs text-brand hover:text-brand-light font-medium"
          >
            Mark all read
          </button>
          <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600">
            <XMarkIcon class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- List -->
      <div class="max-h-96 overflow-y-auto">
        <div v-if="loading && !notifications.length" class="p-8 text-center text-gray-400 text-sm">Loading...</div>
        <div v-else-if="!notifications.length" class="p-8 text-center text-gray-400 text-sm">No notifications yet</div>

        <div
          v-for="n in notifications"
          :key="n.id"
          @click="markRead(n.id)"
          :class="[
            'flex gap-3 px-4 py-3 border-b border-gray-50 cursor-pointer transition-colors border-l-4',
            priorityBorder(n.priority),
            n.read_at ? 'bg-white' : 'bg-blue-50/40',
          ]"
        >
          <span class="text-lg flex-shrink-0 mt-0.5">{{ typeIcon(n.type) }}</span>
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
              <p :class="['text-sm truncate', n.read_at ? 'text-gray-600' : 'text-gray-800 font-medium']">
                {{ n.title }}
              </p>
              <span class="text-[10px] text-gray-400 whitespace-nowrap ml-2">{{ timeAgo(n.created_at) }}</span>
            </div>
            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ n.body }}</p>
          </div>
          <span v-if="!n.read_at" class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2" />
        </div>
      </div>
    </div>

    <!-- Click outside to close -->
    <div v-if="isOpen" class="fixed inset-0 z-40" @click="isOpen = false" />
  </div>
</template>

<!--
Аннотация (RU):
NotificationsPanel.vue — компонент колокольчика в Topbar.
Real-time WebSocket: private channel user.{id}, событие .notification.
Browser Notification API для desktop push.
Dropdown: 20 последних уведомлений, mark read (single/all).
Priority: urgent (red border), high (orange), normal (blue), low (gray).
12 типов с emoji иконками. Unread badge (красный кружок).
Файл: src/components/NotificationsPanel.vue
-->
