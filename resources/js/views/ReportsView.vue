<script setup lang="ts">
// =====================================================
// FILE: src/views/reports/ReportsView.vue
// Report Generator + Scheduled + History
// =====================================================

import { ref, onMounted } from 'vue'
import api from '@/composables/useApi'
import { useToast } from 'vue-toastification'
import {
  DocumentArrowDownIcon, ClockIcon, CalendarDaysIcon,
  PlusIcon, TrashIcon, ArrowPathIcon,
} from '@heroicons/vue/24/outline'

const toast = useToast()

interface ReportType {
  name: string
  description: string
  formats: string[]
  parameters: string[]
}

const reportTypes = ref<Record<string, ReportType>>({})
const history = ref<any[]>([])
const scheduled = ref<any[]>([])
const activeTab = ref<'generate' | 'scheduled' | 'history'>('generate')
const loading = ref(false)

// Form
const selectedType = ref('')
const selectedFormat = ref('pdf')
const dateFrom = ref('')
const dateTo = ref('')
const generating = ref(false)

// Scheduled form
const showScheduleForm = ref(false)
const schedForm = ref({ report_type: '', format: 'pdf', frequency: 'weekly', recipients: '' })

// =====================================================
// DATA
// =====================================================

async function loadTypes() {
  try {
    const { data } = await api.get('/reports/types')
    reportTypes.value = data.data ?? {}
  } catch { /* */ }
}

async function loadHistory() {
  try {
    const { data } = await api.get('/reports/history')
    history.value = data.data ?? []
  } catch { /* */ }
}

async function loadScheduled() {
  try {
    const { data } = await api.get('/reports/scheduled')
    scheduled.value = data.data ?? []
  } catch { /* */ }
}

// =====================================================
// ACTIONS
// =====================================================

async function generateReport() {
  if (!selectedType.value) return
  generating.value = true
  try {
    const { data } = await api.post('/reports/generate', {
      type: selectedType.value,
      format: selectedFormat.value,
      date_from: dateFrom.value || undefined,
      date_to: dateTo.value || undefined,
    })

    if (data.data?.download_url) {
      window.open(data.data.download_url, '_blank')
      toast.success(`Report generated: ${data.data.filename}`)
    } else if (data.data?.data) {
      toast.success('Report data generated (JSON)')
    }

    loadHistory()
  } catch (e: any) {
    toast.error(e.response?.data?.message || 'Generation failed')
  }
  generating.value = false
}

async function createScheduled() {
  if (!schedForm.value.report_type || !schedForm.value.recipients) return
  try {
    await api.post('/reports/scheduled', {
      ...schedForm.value,
      recipients: schedForm.value.recipients.split(',').map((e: string) => e.trim()),
    })
    toast.success('Scheduled report created')
    showScheduleForm.value = false
    schedForm.value = { report_type: '', format: 'pdf', frequency: 'weekly', recipients: '' }
    loadScheduled()
  } catch { /* */ }
}

async function deleteScheduled(id: number) {
  if (!confirm('Delete this scheduled report?')) return
  await api.delete(`/reports/scheduled/${id}`)
  loadScheduled()
}

// =====================================================
// HELPERS
// =====================================================

function formatDate(d: string): string {
  return d ? new Date(d).toLocaleDateString('pl-PL', { year: 'numeric', month: 'short', day: 'numeric' }) : '—'
}

function formatSize(bytes: number): string {
  if (!bytes) return '—'
  if (bytes < 1024) return `${bytes} B`
  if (bytes < 1048576) return `${(bytes / 1024).toFixed(1)} KB`
  return `${(bytes / 1048576).toFixed(1)} MB`
}

const typeLabels: Record<string, string> = {
  leads_summary: '👤 Leads Summary',
  cases_status: '📁 Cases Status',
  financial: '💰 Financial',
  client_portfolio: '🏢 Client Portfolio',
  manager_performance: '📊 Manager Performance',
  ads_roi: '📢 Ads ROI',
  news_pipeline: '📰 News Pipeline',
  document_expiry: '📄 Document Expiry',
}

onMounted(() => { loadTypes(); loadHistory(); loadScheduled() })
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Reports</h1>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 bg-gray-100 rounded-lg p-1 w-fit">
      <button v-for="tab in (['generate', 'scheduled', 'history'] as const)" :key="tab"
        @click="activeTab = tab"
        :class="['px-4 py-2 rounded-md text-sm font-medium transition', activeTab === tab ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700']">
        {{ tab === 'generate' ? '📋 Generate' : tab === 'scheduled' ? '⏰ Scheduled' : '📂 History' }}
      </button>
    </div>

    <!-- ======================================= -->
    <!-- TAB: GENERATE -->
    <!-- ======================================= -->
    <div v-if="activeTab === 'generate'" class="max-w-2xl">
      <div class="bg-white rounded-xl p-6 border border-gray-100 space-y-5">
        <!-- Report type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
          <select v-model="selectedType" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">Select report...</option>
            <option v-for="(rt, key) in reportTypes" :key="key" :value="key">
              {{ typeLabels[key] || rt.name }}
            </option>
          </select>
          <p v-if="selectedType && reportTypes[selectedType]" class="text-xs text-gray-400 mt-1">
            {{ reportTypes[selectedType].description }}
          </p>
        </div>

        <!-- Format -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
          <div class="flex gap-3">
            <label v-for="f in (reportTypes[selectedType]?.formats || ['pdf','xlsx','json'])" :key="f"
              :class="['flex items-center gap-2 px-4 py-2 border rounded-lg cursor-pointer text-sm transition',
                selectedFormat === f ? 'border-brand bg-brand-50 text-brand' : 'border-gray-200 text-gray-600 hover:border-gray-300']">
              <input type="radio" v-model="selectedFormat" :value="f" class="sr-only" />
              {{ f.toUpperCase() }}
            </label>
          </div>
        </div>

        <!-- Date range -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
            <input type="date" v-model="dateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
            <input type="date" v-model="dateTo" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" />
          </div>
        </div>

        <!-- Generate button -->
        <button
          @click="generateReport"
          :disabled="!selectedType || generating"
          class="w-full flex items-center justify-center gap-2 py-2.5 bg-brand text-white rounded-lg font-medium hover:bg-brand-light transition disabled:opacity-50"
        >
          <DocumentArrowDownIcon class="w-5 h-5" />
          {{ generating ? 'Generating...' : 'Generate Report' }}
        </button>
      </div>
    </div>

    <!-- ======================================= -->
    <!-- TAB: SCHEDULED -->
    <!-- ======================================= -->
    <div v-if="activeTab === 'scheduled'">
      <button @click="showScheduleForm = !showScheduleForm"
        class="flex items-center gap-2 mb-4 px-4 py-2 bg-brand text-white text-sm rounded-lg hover:bg-brand-light">
        <PlusIcon class="w-4 h-4" /> New Schedule
      </button>

      <!-- Create form -->
      <div v-if="showScheduleForm" class="bg-white rounded-xl p-6 border border-gray-100 mb-6 max-w-lg space-y-4">
        <select v-model="schedForm.report_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
          <option value="">Select report type...</option>
          <option v-for="(rt, key) in reportTypes" :key="key" :value="key">{{ rt.name }}</option>
        </select>
        <div class="grid grid-cols-2 gap-3">
          <select v-model="schedForm.format" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="pdf">PDF</option>
            <option value="xlsx">Excel</option>
          </select>
          <select v-model="schedForm.frequency" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="daily">Daily</option>
            <option value="weekly">Weekly (Mon)</option>
            <option value="monthly">Monthly (1st)</option>
          </select>
        </div>
        <input v-model="schedForm.recipients" placeholder="email1@example.com, email2@example.com"
          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" />
        <button @click="createScheduled" class="px-6 py-2 bg-brand text-white text-sm rounded-lg hover:bg-brand-light">Create</button>
      </div>

      <!-- List -->
      <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50"><tr>
            <th class="text-left px-4 py-3 font-medium">Report</th>
            <th class="text-left px-4 py-3 font-medium">Format</th>
            <th class="text-left px-4 py-3 font-medium">Frequency</th>
            <th class="text-left px-4 py-3 font-medium hidden md:table-cell">Recipients</th>
            <th class="text-left px-4 py-3 font-medium hidden lg:table-cell">Next Run</th>
            <th class="text-left px-4 py-3 font-medium">Actions</th>
          </tr></thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="s in scheduled" :key="s.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 font-medium">{{ typeLabels[s.report_type] || s.report_type }}</td>
              <td class="px-4 py-3 uppercase text-gray-500">{{ s.format }}</td>
              <td class="px-4 py-3 capitalize text-gray-500">{{ s.frequency }}</td>
              <td class="px-4 py-3 text-gray-400 hidden md:table-cell text-xs">{{ (s.recipients || []).join(', ') }}</td>
              <td class="px-4 py-3 text-gray-400 hidden lg:table-cell">{{ formatDate(s.next_run_at) }}</td>
              <td class="px-4 py-3">
                <button @click="deleteScheduled(s.id)" class="text-red-400 hover:text-red-600"><TrashIcon class="w-4 h-4" /></button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="!scheduled.length" class="p-8 text-center text-gray-400 text-sm">No scheduled reports</div>
      </div>
    </div>

    <!-- ======================================= -->
    <!-- TAB: HISTORY -->
    <!-- ======================================= -->
    <div v-if="activeTab === 'history'">
      <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50"><tr>
            <th class="text-left px-4 py-3 font-medium">Report</th>
            <th class="text-left px-4 py-3 font-medium">Format</th>
            <th class="text-left px-4 py-3 font-medium hidden md:table-cell">Size</th>
            <th class="text-left px-4 py-3 font-medium">Generated</th>
            <th class="text-left px-4 py-3 font-medium">Download</th>
          </tr></thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="r in history" :key="r.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 font-medium">{{ typeLabels[r.report_type] || r.report_type }}</td>
              <td class="px-4 py-3 uppercase text-gray-500">{{ r.format }}</td>
              <td class="px-4 py-3 text-gray-400 hidden md:table-cell">{{ formatSize(r.file_size) }}</td>
              <td class="px-4 py-3 text-gray-400">{{ formatDate(r.created_at) }}</td>
              <td class="px-4 py-3">
                <a :href="`/api/v1/reports/download?path=${r.path}`" target="_blank"
                  class="text-brand hover:text-brand-light font-medium text-xs">Download</a>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="!history.length" class="p-8 text-center text-gray-400 text-sm">No reports generated yet</div>
      </div>
    </div>
  </div>
</template>

<!--
Аннотация (RU):
ReportsView.vue — страница отчётов в админ-панели.
3 вкладки: Generate (on-demand), Scheduled (расписание), History (история).

Generate: выбор типа (8 отчётов), формат (PDF/XLSX/JSON), date range.
Генерация → автоскачивание файла.

Scheduled: CRUD. Frequency: daily/weekly/monthly. Recipients: email list.
Отправка PDF/XLSX по email в 7:00.

History: список сгенерированных отчётов с download link.
8 типов отчётов: leads, cases, financial, clients, managers, ads, news, docs.
Файл: src/views/reports/ReportsView.vue
-->
