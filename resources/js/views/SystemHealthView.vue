<script setup lang="ts">
// =====================================================
// FILE: src/views/system/SystemHealthView.vue
// System Health Dashboard + Settings + Cache + Maintenance
// =====================================================

import { ref, onMounted, computed } from 'vue'
import api from '@/composables/useApi'
import { useToast } from 'vue-toastification'
import {
  ServerStackIcon, CpuChipIcon, CircleStackIcon,
  WrenchScrewdriverIcon, ArrowPathIcon, SignalIcon,
  CheckCircleIcon, XCircleIcon, ExclamationTriangleIcon,
} from '@heroicons/vue/24/outline'

const toast = useToast()
const health = ref<any>(null)
const settings = ref<any>(null)
const activeTab = ref<'health' | 'integrations' | 'tools'>('health')
const loading = ref(false)

// =====================================================
// DATA
// =====================================================

async function loadHealth() {
  loading.value = true
  try {
    const { data } = await api.get('/system/health')
    health.value = data.data
  } catch { /* */ }
  loading.value = false
}

async function loadSettings() {
  try {
    const { data } = await api.get('/system/settings')
    settings.value = data.data
  } catch { /* */ }
}

// =====================================================
// ACTIONS
// =====================================================

async function clearCache(type: string) {
  try {
    await api.post('/system/cache/clear', { type })
    toast.success(`Cache cleared: ${type}`)
  } catch { toast.error('Failed to clear cache') }
}

async function optimizeCache() {
  try {
    await api.post('/system/cache/optimize')
    toast.success('Cache optimized')
  } catch { toast.error('Failed to optimize') }
}

async function toggleMaintenance() {
  const maint = health.value?.services?.maintenance
  if (maint?.active) {
    await api.post('/system/maintenance/disable')
    toast.success('Maintenance mode disabled')
  } else {
    const reason = prompt('Maintenance reason:') || 'Scheduled maintenance'
    await api.post('/system/maintenance/enable', { reason })
    toast.success('Maintenance mode enabled')
  }
  loadHealth()
}

// =====================================================
// HELPERS
// =====================================================

function statusIcon(status: string) {
  if (status === 'healthy' || status === 'configured' || status === 'operational') return CheckCircleIcon
  if (status === 'warning') return ExclamationTriangleIcon
  return XCircleIcon
}

function statusColor(status: string): string {
  if (status === 'healthy' || status === 'configured' || status === 'operational') return 'text-green-500'
  if (status === 'warning') return 'text-yellow-500'
  if (status === 'unknown') return 'text-gray-400'
  return 'text-red-500'
}

function statusBg(status: string): string {
  if (status === 'healthy' || status === 'configured') return 'bg-green-50 border-green-200'
  if (status === 'warning') return 'bg-yellow-50 border-yellow-200'
  return 'bg-red-50 border-red-200'
}

onMounted(() => { loadHealth(); loadSettings() })
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">System Health</h1>
      <button @click="loadHealth" :disabled="loading"
        class="flex items-center gap-2 px-3 py-2 text-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 disabled:opacity-50">
        <ArrowPathIcon class="w-4 h-4" :class="loading ? 'animate-spin' : ''" /> Refresh
      </button>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 bg-gray-100 rounded-lg p-1 w-fit">
      <button v-for="tab in (['health', 'integrations', 'tools'] as const)" :key="tab"
        @click="activeTab = tab"
        :class="['px-4 py-2 rounded-md text-sm font-medium transition', activeTab === tab ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500']">
        {{ tab === 'health' ? '🏥 Health' : tab === 'integrations' ? '🔌 Integrations' : '🔧 Tools' }}
      </button>
    </div>

    <!-- ============================== -->
    <!-- TAB: HEALTH -->
    <!-- ============================== -->
    <div v-if="activeTab === 'health' && health" class="space-y-6">
      <!-- Service cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Database -->
        <div :class="['rounded-xl p-5 border', statusBg(health.services?.database?.status)]">
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
              <CircleStackIcon class="w-5 h-5 text-gray-600" />
              <h3 class="font-semibold text-gray-700">MySQL</h3>
            </div>
            <component :is="statusIcon(health.services?.database?.status)" :class="['w-5 h-5', statusColor(health.services?.database?.status)]" />
          </div>
          <div class="space-y-1 text-sm text-gray-600">
            <div class="flex justify-between"><span>Version</span><span class="font-mono">{{ health.services?.database?.version }}</span></div>
            <div class="flex justify-between"><span>Latency</span><span>{{ health.services?.database?.latency_ms }}ms</span></div>
            <div class="flex justify-between"><span>Size</span><span>{{ health.services?.database?.size_mb }} MB</span></div>
            <div class="flex justify-between"><span>Connections</span><span>{{ health.services?.database?.connections }}/{{ health.services?.database?.max_connections }}</span></div>
          </div>
        </div>

        <!-- Redis -->
        <div :class="['rounded-xl p-5 border', statusBg(health.services?.redis?.status)]">
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
              <SignalIcon class="w-5 h-5 text-gray-600" />
              <h3 class="font-semibold text-gray-700">Redis</h3>
            </div>
            <component :is="statusIcon(health.services?.redis?.status)" :class="['w-5 h-5', statusColor(health.services?.redis?.status)]" />
          </div>
          <div class="space-y-1 text-sm text-gray-600">
            <div class="flex justify-between"><span>Version</span><span class="font-mono">{{ health.services?.redis?.version }}</span></div>
            <div class="flex justify-between"><span>Memory</span><span>{{ health.services?.redis?.used_memory_mb }}/{{ health.services?.redis?.max_memory_mb }} MB</span></div>
            <div class="flex justify-between"><span>Keys</span><span>{{ health.services?.redis?.keys?.toLocaleString() }}</span></div>
            <div class="flex justify-between"><span>Uptime</span><span>{{ health.services?.redis?.uptime_days }}d</span></div>
          </div>
        </div>

        <!-- Storage -->
        <div :class="['rounded-xl p-5 border', statusBg(health.services?.storage?.status)]">
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
              <ServerStackIcon class="w-5 h-5 text-gray-600" />
              <h3 class="font-semibold text-gray-700">Storage</h3>
            </div>
            <component :is="statusIcon(health.services?.storage?.status)" :class="['w-5 h-5', statusColor(health.services?.storage?.status)]" />
          </div>
          <div class="space-y-1 text-sm text-gray-600">
            <div class="flex justify-between"><span>Usage</span><span>{{ health.services?.storage?.usage_pct }}%</span></div>
            <div class="flex justify-between"><span>Used</span><span>{{ health.services?.storage?.used_gb }} GB</span></div>
            <div class="flex justify-between"><span>Free</span><span>{{ health.services?.storage?.free_gb }} GB</span></div>
          </div>
          <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
            <div class="h-2 rounded-full" :style="{width: (health.services?.storage?.usage_pct || 0) + '%'}"
              :class="(health.services?.storage?.usage_pct || 0) > 90 ? 'bg-red-500' : (health.services?.storage?.usage_pct || 0) > 70 ? 'bg-yellow-500' : 'bg-green-500'" />
          </div>
        </div>

        <!-- Queue -->
        <div :class="['rounded-xl p-5 border', statusBg(health.services?.queue?.status)]">
          <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-700">⚙️ Queue</h3>
            <component :is="statusIcon(health.services?.queue?.status)" :class="['w-5 h-5', statusColor(health.services?.queue?.status)]" />
          </div>
          <div class="space-y-1 text-sm text-gray-600">
            <div class="flex justify-between"><span>Pending</span><span>{{ health.services?.queue?.pending_jobs }}</span></div>
            <div class="flex justify-between"><span>Failed</span><span :class="health.services?.queue?.failed_jobs > 0 ? 'text-red-500 font-bold' : ''">{{ health.services?.queue?.failed_jobs }}</span></div>
          </div>
        </div>

        <!-- n8n -->
        <div :class="['rounded-xl p-5 border', statusBg(health.services?.n8n?.status)]">
          <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-700">🔗 n8n</h3>
            <component :is="statusIcon(health.services?.n8n?.status)" :class="['w-5 h-5', statusColor(health.services?.n8n?.status)]" />
          </div>
          <div class="space-y-1 text-sm text-gray-600">
            <div class="flex justify-between"><span>Workflows</span><span>{{ health.services?.n8n?.active_workflows }}/{{ health.services?.n8n?.total_workflows }}</span></div>
          </div>
        </div>

        <!-- Reverb -->
        <div :class="['rounded-xl p-5 border', statusBg(health.services?.reverb?.status)]">
          <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-700">📡 WebSocket</h3>
            <component :is="statusIcon(health.services?.reverb?.status)" :class="['w-5 h-5', statusColor(health.services?.reverb?.status)]" />
          </div>
          <div class="text-sm text-gray-600">{{ health.services?.reverb?.status }}</div>
        </div>
      </div>

      <!-- Server metrics -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2"><CpuChipIcon class="w-5 h-5" /> Server</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
          <div><span class="text-gray-400">PHP</span><p class="font-mono font-medium">{{ health.server?.php_version }}</p></div>
          <div><span class="text-gray-400">Laravel</span><p class="font-mono font-medium">{{ health.application?.laravel_version }}</p></div>
          <div><span class="text-gray-400">CPU Load</span><p class="font-medium">{{ health.server?.cpu_load?.['1min'] }} / {{ health.server?.cpu_load?.['5min'] }} / {{ health.server?.cpu_load?.['15min'] }}</p></div>
          <div><span class="text-gray-400">PHP Memory</span><p class="font-medium">{{ health.server?.memory?.php_usage_mb }} MB (peak: {{ health.server?.memory?.php_peak_mb }} MB)</p></div>
          <div><span class="text-gray-400">Environment</span><p class="font-medium capitalize">{{ health.application?.environment }}</p></div>
          <div><span class="text-gray-400">OPcache</span><p class="font-medium">{{ health.application?.opcache_enabled ? '✅ Enabled' : '❌ Disabled' }}</p></div>
          <div><span class="text-gray-400">Debug Mode</span><p :class="health.application?.debug_mode ? 'text-red-500 font-bold' : 'text-green-600'">{{ health.application?.debug_mode ? '⚠️ ON' : 'OFF' }}</p></div>
          <div><span class="text-gray-400">Uptime</span><p class="font-medium">{{ health.server?.uptime }}</p></div>
        </div>
      </div>

      <!-- Table counts -->
      <div v-if="health.application?.table_counts" class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">📊 Database Records</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
          <div v-for="(count, table) in health.application.table_counts" :key="table" class="text-center p-3 bg-gray-50 rounded-lg">
            <p class="text-lg font-bold text-gray-800">{{ count?.toLocaleString() }}</p>
            <p class="text-xs text-gray-400 capitalize">{{ table }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================== -->
    <!-- TAB: INTEGRATIONS -->
    <!-- ============================== -->
    <div v-if="activeTab === 'integrations' && settings?.integrations" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="(info, name) in settings.integrations" :key="name"
        :class="['rounded-xl p-4 border flex items-center justify-between', info.configured ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200']">
        <span class="font-medium text-sm text-gray-700 capitalize">{{ String(name).replace(/_/g, ' ') }}</span>
        <span :class="['text-xs font-medium px-2 py-1 rounded-full', info.configured ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500']">
          {{ info.configured ? '✅ Connected' : '⚪ Not configured' }}
        </span>
      </div>
    </div>

    <!-- ============================== -->
    <!-- TAB: TOOLS -->
    <!-- ============================== -->
    <div v-if="activeTab === 'tools'" class="max-w-2xl space-y-6">
      <!-- Cache -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">🗑️ Cache Management</h3>
        <div class="flex flex-wrap gap-2">
          <button @click="clearCache('config')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg hover:bg-gray-200">Clear Config</button>
          <button @click="clearCache('route')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg hover:bg-gray-200">Clear Routes</button>
          <button @click="clearCache('view')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg hover:bg-gray-200">Clear Views</button>
          <button @click="clearCache('cache')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg hover:bg-gray-200">Clear App Cache</button>
          <button @click="clearCache('all')" class="px-4 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200">Clear ALL</button>
          <button @click="optimizeCache()" class="px-4 py-2 text-sm bg-brand text-white rounded-lg hover:bg-brand-light">⚡ Optimize</button>
        </div>
      </div>

      <!-- Maintenance -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">🔧 Maintenance Mode</h3>
        <button @click="toggleMaintenance()"
          :class="['px-6 py-2 text-sm rounded-lg font-medium',
            health?.maintenance?.active ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-orange-500 text-white hover:bg-orange-600']">
          {{ health?.maintenance?.active ? 'Disable Maintenance' : 'Enable Maintenance' }}
        </button>
      </div>
    </div>
  </div>
</template>

<!--
Аннотация (RU):
SystemHealthView.vue — системная панель здоровья.
3 вкладки: Health, Integrations, Tools.

Health: 6 service cards (MySQL, Redis, Storage, Queue, n8n, WebSocket).
Цветовая индикация: green (healthy), yellow (warning), red (down).
Server metrics: PHP version, Laravel, CPU load, memory, OPcache.
Database records: table counts для 10 основных таблиц.

Integrations: 13 интеграций (Google, Meta, TikTok, WhatsApp, Telegram, AI...).
Connected/Not configured status.

Tools: Cache management (clear config/route/view/cache/all, optimize).
Maintenance mode toggle.
Файл: src/views/system/SystemHealthView.vue
-->
