<script setup lang="ts">
// =====================================================
// FILE: src/views/leads/LeadsListView.vue
// Leads list with filters + status badges + search
// =====================================================

import { onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useLeadsStore } from '@/stores/leads'
import { PlusIcon, FunnelIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline'

const store = useLeadsStore()
const router = useRouter()

onMounted(() => store.load())
watch(() => store.filters, () => store.load(), { deep: true })

const statuses = ['', 'new', 'contacted', 'consultation', 'contract', 'paid', 'rejected', 'spam']
const statusColors: Record<string, string> = {
  new: 'bg-blue-100 text-blue-700',
  contacted: 'bg-yellow-100 text-yellow-700',
  consultation: 'bg-purple-100 text-purple-700',
  contract: 'bg-indigo-100 text-indigo-700',
  paid: 'bg-green-100 text-green-700',
  rejected: 'bg-red-100 text-red-700',
  spam: 'bg-gray-100 text-gray-500',
}
const priorityIcon: Record<string, string> = { high: '🔴', medium: '🟡', low: '🟢' }
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Leads</h1>
      <router-link
        to="/leads/create"
        class="flex items-center gap-2 px-4 py-2 bg-brand text-white text-sm rounded-lg hover:bg-brand-light transition"
      >
        <PlusIcon class="w-4 h-4" /> New Lead
      </router-link>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mb-6">
      <div class="relative flex-1 min-w-[200px]">
        <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
        <input
          v-model="store.filters.search"
          placeholder="Search by name, phone, email..."
          class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-brand/40 focus:border-brand"
        />
      </div>
      <select
        v-model="store.filters.status"
        class="px-3 py-2 border border-gray-300 rounded-lg text-sm"
      >
        <option value="">All statuses</option>
        <option v-for="s in statuses.slice(1)" :key="s" :value="s">{{ s }}</option>
      </select>
    </div>

    <!-- Total count -->
    <p class="text-sm text-gray-500 mb-4">{{ store.total }} leads found</p>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="text-left px-4 py-3 font-medium">Name</th>
            <th class="text-left px-4 py-3 font-medium hidden md:table-cell">Phone</th>
            <th class="text-left px-4 py-3 font-medium hidden lg:table-cell">Source</th>
            <th class="text-left px-4 py-3 font-medium">Status</th>
            <th class="text-left px-4 py-3 font-medium hidden lg:table-cell">Priority</th>
            <th class="text-left px-4 py-3 font-medium hidden xl:table-cell">Created</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr
            v-for="lead in store.leads"
            :key="lead.id"
            class="hover:bg-gray-50 cursor-pointer transition-colors"
            @click="router.push(`/leads/${lead.id}`)"
          >
            <td class="px-4 py-3 font-medium text-gray-800">{{ lead.name }}</td>
            <td class="px-4 py-3 text-gray-500 hidden md:table-cell">{{ lead.phone }}</td>
            <td class="px-4 py-3 text-gray-500 hidden lg:table-cell capitalize">{{ lead.source?.replace('_', ' ') }}</td>
            <td class="px-4 py-3">
              <span :class="['px-2 py-1 rounded-full text-xs font-medium', statusColors[lead.status] || 'bg-gray-100 text-gray-600']">
                {{ lead.status }}
              </span>
            </td>
            <td class="px-4 py-3 hidden lg:table-cell">{{ priorityIcon[lead.priority] || '' }} {{ lead.priority }}</td>
            <td class="px-4 py-3 text-gray-400 hidden xl:table-cell">{{ new Date(lead.created_at).toLocaleDateString('pl-PL') }}</td>
          </tr>
        </tbody>
      </table>

      <div v-if="store.loading" class="p-8 text-center text-gray-400">Loading...</div>
      <div v-else-if="!store.leads.length" class="p-8 text-center text-gray-400">No leads found</div>
    </div>
  </div>
</template>

<!--
Аннотация (RU):
LeadsListView.vue — список лидов.
Фильтры: search (name/phone/email), status dropdown.
Таблица: name, phone, source, status (цветной badge), priority (emoji), created.
Click → /leads/:id. Responsive: скрытие колонок на мобильных.
Файл: src/views/leads/LeadsListView.vue
-->
