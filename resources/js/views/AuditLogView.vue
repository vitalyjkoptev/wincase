<script setup lang="ts">
// =====================================================
// FILE: src/views/audit/AuditLogView.vue
// Audit Logs + Security Report + Activity Stats
// =====================================================

import { ref, onMounted } from 'vue'
import api from '@/composables/useApi'
import {
  ShieldCheckIcon, UserGroupIcon, ClockIcon,
  FunnelIcon, MagnifyingGlassIcon, ExclamationTriangleIcon,
} from '@heroicons/vue/24/outline'

const activeTab = ref<'logs' | 'security' | 'stats'>('logs')
const logs = ref<any[]>([])
const meta = ref({ total: 0, current_page: 1, last_page: 1 })
const security = ref<any>(null)
const stats = ref<any>(null)
const loading = ref(false)

// Filters
const filters = ref({
  action: '', entity_type: '', user_id: '',
  date_from: '', date_to: '', search: '',
})

// =====================================================
// DATA
// =====================================================

async function loadLogs(page = 1) {
  loading.value = true
  try {
    const params: any = { per_page: 30, page }
    Object.entries(filters.value).forEach(([k, v]) => { if (v) params[k] = v })
    const { data } = await api.get('/audit/logs', { params })
    logs.value = data.data?.data ?? []
    meta.value = data.data?.meta ?? meta.value
  } catch { /* */ }
  loading.value = false
}

async function loadSecurity() {
  try {
    const { data } = await api.get('/audit/security', { params: { days: 7 } })
    security.value = data.data
  } catch { /* */ }
}

async function loadStats() {
  try {
    const { data } = await api.get('/audit/stats', { params: { days: 30 } })
    stats.value = data.data
  } catch { /* */ }
}

// =====================================================
// HELPERS
// =====================================================

function actionIcon(action: string): string {
  const icons: Record<string, string> = {
    login: '🔑', logout: '🚪', login_failed: '🚫', '2fa_enabled': '🔐',
    password_changed: '🔒', created: '➕', updated: '✏️', deleted: '🗑️',
    status_changed: '🔄', assigned: '👤', role_changed: '👑', exported: '📤',
    document_viewed: '👁️', document_uploaded: '📎', document_downloaded: '⬇️',
  }
  return icons[action] || '📝'
}

function actionColor(action: string): string {
  if (action.includes('failed') || action === 'deleted') return 'text-red-600 bg-red-50'
  if (action.includes('login') || action.includes('password') || action.includes('2fa')) return 'text-blue-600 bg-blue-50'
  if (action === 'created') return 'text-green-600 bg-green-50'
  if (action === 'updated' || action === 'status_changed') return 'text-yellow-600 bg-yellow-50'
  if (action === 'role_changed') return 'text-purple-600 bg-purple-50'
  return 'text-gray-600 bg-gray-50'
}

function timeStr(ts: string): string {
  return new Date(ts).toLocaleString('pl-PL', {
    day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit',
  })
}

function resetFilters() {
  filters.value = { action: '', entity_type: '', user_id: '', date_from: '', date_to: '', search: '' }
  loadLogs()
}

onMounted(() => { loadLogs(); loadSecurity(); loadStats() })
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Audit Log</h1>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 bg-gray-100 rounded-lg p-1 w-fit">
      <button v-for="tab in (['logs', 'security', 'stats'] as const)" :key="tab"
        @click="activeTab = tab"
        :class="['px-4 py-2 rounded-md text-sm font-medium transition', activeTab === tab ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500']">
        {{ tab === 'logs' ? '📋 Activity Logs' : tab === 'security' ? '🛡️ Security' : '📊 Statistics' }}
      </button>
    </div>

    <!-- ============================== -->
    <!-- TAB: LOGS -->
    <!-- ============================== -->
    <div v-if="activeTab === 'logs'">
      <!-- Filters -->
      <div class="flex flex-wrap gap-3 mb-4 items-end">
        <div class="relative">
          <MagnifyingGlassIcon class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" />
          <input v-model="filters.search" @keyup.enter="loadLogs()" placeholder="Search..."
            class="pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm w-48" />
        </div>
        <select v-model="filters.action" @change="loadLogs()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
          <option value="">All actions</option>
          <option v-for="a in ['login','logout','login_failed','created','updated','deleted','status_changed','assigned','role_changed','exported']" :key="a" :value="a">{{ a }}</option>
        </select>
        <select v-model="filters.entity_type" @change="loadLogs()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
          <option value="">All entities</option>
          <option v-for="e in ['auth','lead','client','case','task','document','user','invoice','report']" :key="e" :value="e">{{ e }}</option>
        </select>
        <input v-model="filters.date_from" type="date" @change="loadLogs()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" />
        <input v-model="filters.date_to" type="date" @change="loadLogs()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" />
        <button @click="resetFilters()" class="px-3 py-2 text-xs text-gray-500 hover:text-gray-700">Clear</button>
      </div>

      <!-- Logs list -->
      <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div v-for="log in logs" :key="log.id" class="flex items-start gap-3 px-4 py-3 border-b border-gray-50 hover:bg-gray-50/50">
          <span :class="['w-8 h-8 rounded-full flex items-center justify-center text-sm flex-shrink-0', actionColor(log.action)]">
            {{ actionIcon(log.action) }}
          </span>
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
              <p class="text-sm text-gray-800 font-medium">{{ log.description }}</p>
              <span class="text-xs text-gray-400 whitespace-nowrap ml-2">{{ timeStr(log.created_at) }}</span>
            </div>
            <div class="flex items-center gap-3 mt-1 text-xs text-gray-400">
              <span>{{ log.user_name }} ({{ log.user_role }})</span>
              <span v-if="log.ip_address">IP: {{ log.ip_address }}</span>
              <span class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-500">{{ log.entity_type }}</span>
            </div>
            <!-- Changed values -->
            <div v-if="log.old_values || log.new_values" class="mt-2 text-xs bg-gray-50 rounded p-2">
              <div v-if="log.old_values" class="text-red-500">- {{ JSON.stringify(log.old_values).slice(0, 120) }}</div>
              <div v-if="log.new_values" class="text-green-600">+ {{ JSON.stringify(log.new_values).slice(0, 120) }}</div>
            </div>
          </div>
        </div>
        <div v-if="loading" class="p-8 text-center text-gray-400">Loading...</div>
        <div v-else-if="!logs.length" class="p-8 text-center text-gray-400">No audit logs found</div>
      </div>

      <!-- Pagination -->
      <div v-if="meta.last_page > 1" class="flex items-center justify-center gap-2 mt-4">
        <button v-for="p in Math.min(meta.last_page, 10)" :key="p" @click="loadLogs(p)"
          :class="['px-3 py-1 rounded text-sm', meta.current_page === p ? 'bg-brand text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
          {{ p }}
        </button>
      </div>
    </div>

    <!-- ============================== -->
    <!-- TAB: SECURITY -->
    <!-- ============================== -->
    <div v-if="activeTab === 'security' && security" class="space-y-6">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-100">
          <p class="text-sm text-gray-500">Successful Logins (7d)</p>
          <p class="text-2xl font-bold text-green-600 mt-1">{{ security.successful_logins }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
          <p class="text-sm text-gray-500">Failed Logins (7d)</p>
          <p class="text-2xl font-bold text-red-500 mt-1">{{ security.failed_logins }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
          <p class="text-sm text-gray-500">Password Changes</p>
          <p class="text-2xl font-bold text-blue-600 mt-1">{{ security.password_changes }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-100">
          <p class="text-sm text-gray-500">Active Users</p>
          <p class="text-2xl font-bold text-gray-800 mt-1">{{ security.active_users }}</p>
        </div>
      </div>

      <!-- Failed logins by IP -->
      <div v-if="security.failed_logins_by_ip?.length" class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
          <ExclamationTriangleIcon class="w-5 h-5 text-red-500" /> Failed Login Attempts by IP
        </h3>
        <div v-for="ip in security.failed_logins_by_ip" :key="ip.ip_address" class="flex justify-between py-2 text-sm">
          <span class="font-mono text-gray-600">{{ ip.ip_address }}</span>
          <span class="font-bold" :class="ip.attempts > 10 ? 'text-red-500' : 'text-gray-700'">{{ ip.attempts }} attempts</span>
        </div>
      </div>
    </div>

    <!-- ============================== -->
    <!-- TAB: STATS -->
    <!-- ============================== -->
    <div v-if="activeTab === 'stats' && stats" class="space-y-6">
      <div class="bg-white rounded-xl p-5 border border-gray-100">
        <p class="text-sm text-gray-500">Total Events (30d)</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ stats.total_events?.toLocaleString() }}</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-6 border border-gray-100">
          <h3 class="font-semibold text-gray-700 mb-3">By Entity</h3>
          <div v-for="e in stats.by_entity" :key="e.entity_type" class="flex justify-between py-1.5 text-sm">
            <span class="text-gray-500 capitalize">{{ e.entity_type }}</span>
            <span class="font-medium">{{ e.count }}</span>
          </div>
        </div>
        <div class="bg-white rounded-xl p-6 border border-gray-100">
          <h3 class="font-semibold text-gray-700 mb-3">By Action</h3>
          <div v-for="a in stats.by_action?.slice(0, 10)" :key="a.action" class="flex justify-between py-1.5 text-sm">
            <span class="text-gray-500">{{ actionIcon(a.action) }} {{ a.action }}</span>
            <span class="font-medium">{{ a.count }}</span>
          </div>
        </div>
        <div class="bg-white rounded-xl p-6 border border-gray-100">
          <h3 class="font-semibold text-gray-700 mb-3">By User</h3>
          <div v-for="u in stats.by_user" :key="u.user_name" class="flex justify-between py-1.5 text-sm">
            <span class="text-gray-500">{{ u.user_name }}</span>
            <span class="font-medium">{{ u.count }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<!--
Аннотация (RU):
AuditLogView.vue — страница Audit Log в админ-панели.
3 вкладки: Activity Logs, Security, Statistics.

Logs: paginated список с фильтрами (action, entity, date, search).
Каждая запись: icon, description, user (name+role), IP, changed values (diff).

Security: KPI cards (logins, failed, passwords, active users).
Failed logins by IP с красным выделением >10 attempts.

Statistics: total events 30d, by entity, by action, by user.
Файл: src/views/audit/AuditLogView.vue
-->
