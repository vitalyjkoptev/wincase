<script setup lang="ts">
// =====================================================
// FILE: src/views/dashboard/DashboardView.vue
// Dashboard — 12 KPI + section cards + chart
// =====================================================

import { onMounted, computed } from 'vue'
import { useDashboardStore } from '@/stores/dashboard'
import {
  UserPlusIcon, BriefcaseIcon, CurrencyDollarIcon, ClockIcon,
  MegaphoneIcon, GlobeAltIcon, UsersIcon, ChartBarIcon,
  ClipboardDocumentListIcon, UserGroupIcon, CreditCardIcon, CalculatorIcon,
} from '@heroicons/vue/24/outline'

const store = useDashboardStore()
onMounted(() => store.load())

const kpi = computed(() => store.data?.kpi)

interface KpiCard {
  label: string
  key: string
  icon: any
  color: string
  format?: 'number' | 'currency' | 'percent' | 'time'
}

const kpiCards: KpiCard[] = [
  { label: 'Today Leads', key: 'today_leads', icon: UserPlusIcon, color: 'blue' },
  { label: 'Active Cases', key: 'active_cases', icon: BriefcaseIcon, color: 'indigo' },
  { label: 'Monthly Revenue', key: 'monthly_revenue', icon: CurrencyDollarIcon, color: 'green', format: 'currency' },
  { label: 'Avg Response', key: 'avg_response_min', icon: ClockIcon, color: 'orange', format: 'time' },
  { label: 'Ad Spend 7d', key: 'ad_spend_7d', icon: MegaphoneIcon, color: 'red', format: 'currency' },
  { label: 'Organic Users 7d', key: 'organic_users_7d', icon: GlobeAltIcon, color: 'teal' },
  { label: 'Social Followers', key: 'social_followers', icon: UsersIcon, color: 'purple' },
  { label: 'Conversion Rate', key: 'conversion_rate_30d', icon: ChartBarIcon, color: 'cyan', format: 'percent' },
  { label: 'Pending Tasks', key: 'pending_tasks', icon: ClipboardDocumentListIcon, color: 'amber' },
  { label: 'Active Clients', key: 'active_clients', icon: UserGroupIcon, color: 'slate' },
  { label: 'POS Pending', key: 'pos_pending', icon: CreditCardIcon, color: 'rose' },
  { label: 'Tax Burden', key: 'monthly_tax_burden', icon: CalculatorIcon, color: 'violet', format: 'currency' },
]

function formatValue(value: any, format?: string): string {
  if (value == null) return '—'
  switch (format) {
    case 'currency': return `${Number(value).toLocaleString('pl-PL')} PLN`
    case 'percent': return `${value}%`
    case 'time': return `${value} min`
    default: return Number(value).toLocaleString('pl-PL')
  }
}
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
      <button
        class="px-4 py-2 text-sm bg-brand text-white rounded-lg hover:bg-brand-light transition"
        @click="store.load()"
        :disabled="store.loading"
      >
        {{ store.loading ? 'Loading...' : 'Refresh' }}
      </button>
    </div>

    <!-- Loading skeleton -->
    <div v-if="store.loading && !kpi" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div v-for="i in 12" :key="i" class="bg-white rounded-xl p-5 animate-pulse">
        <div class="h-4 bg-gray-200 rounded w-2/3 mb-3" />
        <div class="h-8 bg-gray-200 rounded w-1/2" />
      </div>
    </div>

    <!-- KPI Grid -->
    <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div
        v-for="card in kpiCards"
        :key="card.key"
        class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow"
      >
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm text-gray-500">{{ card.label }}</span>
          <div :class="`p-2 rounded-lg bg-${card.color}-50`">
            <component :is="card.icon" :class="`w-5 h-5 text-${card.color}-500`" />
          </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">
          {{ formatValue(kpi?.[card.key], card.format) }}
        </p>
      </div>
    </div>

    <!-- Section cards (Leads, Finance, Ads, Social, SEO) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mt-8">
      <!-- Leads Section -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">Leads Overview</h3>
        <div class="space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Total (30d)</span>
            <span class="font-medium">{{ store.data?.leads?.total_30d ?? '—' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Today</span>
            <span class="font-medium text-blue-600">{{ store.data?.leads?.today ?? '—' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Unassigned</span>
            <span class="font-medium text-red-500">{{ store.data?.leads?.unassigned ?? '—' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Conversion %</span>
            <span class="font-medium text-green-600">{{ store.data?.leads?.conversion_rate ?? '—' }}%</span>
          </div>
        </div>
        <router-link to="/leads" class="block mt-4 text-sm text-brand hover:text-brand-light font-medium">
          View all leads →
        </router-link>
      </div>

      <!-- Finance Section -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">Finance & POS</h3>
        <div class="space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Monthly Revenue</span>
            <span class="font-medium text-green-600">{{ formatValue(store.data?.finance?.monthly_revenue, 'currency') }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">POS Pending</span>
            <span class="font-medium text-orange-500">{{ store.data?.finance?.pos_pending ?? '—' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Approved Amount</span>
            <span class="font-medium">{{ formatValue(store.data?.finance?.approved_amount, 'currency') }}</span>
          </div>
        </div>
        <router-link to="/pos" class="block mt-4 text-sm text-brand hover:text-brand-light font-medium">
          View POS →
        </router-link>
      </div>

      <!-- Ads Section -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">Advertising</h3>
        <div class="space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Spend (7d)</span>
            <span class="font-medium">{{ formatValue(store.data?.ads?.spend_7d, 'currency') }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Leads (7d)</span>
            <span class="font-medium text-blue-600">{{ store.data?.ads?.leads_7d ?? '—' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Budget Used</span>
            <span class="font-medium">{{ store.data?.ads?.budget_used_pct ?? '—' }}%</span>
          </div>
        </div>
      </div>

      <!-- Social Section -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">Social Media</h3>
        <div class="space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Total Followers</span>
            <span class="font-medium text-purple-600">{{ store.data?.social?.total_followers?.toLocaleString() ?? '—' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Posts (7d)</span>
            <span class="font-medium">{{ store.data?.social?.posts_7d ?? '—' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Active Platforms</span>
            <span class="font-medium">{{ store.data?.social?.active_platforms ?? '—' }}</span>
          </div>
        </div>
      </div>

      <!-- SEO Section -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">SEO</h3>
        <div class="space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">GSC Clicks (7d)</span>
            <span class="font-medium text-teal-600">{{ store.data?.seo?.gsc_clicks_7d?.toLocaleString() ?? '—' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Organic Users (7d)</span>
            <span class="font-medium">{{ store.data?.seo?.organic_users_7d?.toLocaleString() ?? '—' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Network Sites</span>
            <span class="font-medium">{{ store.data?.seo?.network_sites ?? '—' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<!--
Аннотация (RU):
DashboardView.vue — главная страница CRM.
12 KPI карточек (3x4 grid): leads, cases, revenue, response time, ad spend, etc.
5 секций: Leads Overview, Finance & POS, Advertising, Social Media, SEO.
Loading skeleton animation. Refresh button. Currency format PLN.
Responsive: 2→3→4 columns grid.
Файл: src/views/dashboard/DashboardView.vue
-->
