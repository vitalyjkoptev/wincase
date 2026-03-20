<script setup lang="ts">
// =====================================================
// FILE: src/views/news/NewsLiveFeedView.vue
// News Pipeline — Live Feed + Articles + Stats
// =====================================================

import { ref, onMounted, onUnmounted, computed } from 'vue'
import api from '@/composables/useApi'
import {
  SignalIcon, ArrowPathIcon, NewspaperIcon, CheckCircleIcon,
  ExclamationTriangleIcon, XCircleIcon, LanguageIcon,
  RocketLaunchIcon, FunnelIcon, ChartBarIcon,
} from '@heroicons/vue/24/outline'

// =====================================================
// STATE
// =====================================================

interface FeedEvent {
  type: string
  article_id?: number
  source?: string
  target_site?: string
  title?: string
  category?: string
  language?: string
  priority?: string
  plagiarism_score?: number
  url?: string
  error?: string
  message?: string
  timestamp: string
}

interface Article {
  id: number
  source_name: string
  original_title: string
  rewritten_title?: string
  category: string
  priority: string
  status: string
  plagiarism_score?: number
  original_language: string
  rewritten_language?: string
  created_at: string
}

const feedEvents = ref<FeedEvent[]>([])
const articles = ref<Article[]>([])
const stats = ref<any>(null)
const activeTab = ref<'feed' | 'articles' | 'stats' | 'sources'>('feed')
const isConnected = ref(false)
const loading = ref(false)

// Filters
const articleFilter = ref({ status: '', category: '', priority: '' })

// =====================================================
// WEBSOCKET CONNECTION (Reverb)
// =====================================================

let echoChannel: any = null

function connectWebSocket() {
  // @ts-ignore — Echo is injected globally
  if (typeof window.Echo === 'undefined') {
    console.warn('Laravel Echo not initialized, falling back to polling')
    startPolling()
    return
  }

  // @ts-ignore
  echoChannel = window.Echo.channel('news-feed')
    .listen('.news.event', (event: FeedEvent) => {
      feedEvents.value.unshift(event)
      // Keep max 200 events in memory
      if (feedEvents.value.length > 200) feedEvents.value.pop()
    })

  isConnected.value = true
}

// Fallback: polling every 5 seconds
let pollInterval: any = null
function startPolling() {
  pollInterval = setInterval(async () => {
    try {
      const { data } = await api.get('/news/feed', { params: { limit: 20 } })
      const newEvents = data.data?.filter(
        (e: any) => !feedEvents.value.find(f => f.timestamp === e.created_at)
      ) ?? []

      if (newEvents.length > 0) {
        feedEvents.value.unshift(...newEvents.map((e: any) => ({
          type: e.event_type,
          article_id: e.article_id,
          source: e.source,
          target_site: e.target_site,
          title: e.title,
          language: e.language,
          timestamp: e.created_at,
        })))
      }
      isConnected.value = true
    } catch {
      isConnected.value = false
    }
  }, 5000)
}

// =====================================================
// DATA LOADING
// =====================================================

async function loadFeedHistory() {
  try {
    const { data } = await api.get('/news/feed', { params: { limit: 50 } })
    feedEvents.value = (data.data ?? []).map((e: any) => ({
      type: e.event_type,
      article_id: e.article_id,
      source: e.source,
      target_site: e.target_site,
      title: e.title,
      language: e.language,
      timestamp: e.created_at,
    }))
  } catch { /* */ }
}

async function loadArticles() {
  loading.value = true
  try {
    const params: any = { per_page: 30 }
    if (articleFilter.value.status) params.status = articleFilter.value.status
    if (articleFilter.value.category) params.category = articleFilter.value.category
    if (articleFilter.value.priority) params.priority = articleFilter.value.priority

    const { data } = await api.get('/news/articles', { params })
    articles.value = data.data?.data ?? []
  } catch { /* */ }
  loading.value = false
}

async function loadStats() {
  try {
    const { data } = await api.get('/news/statistics', { params: { days: 7 } })
    stats.value = data.data
  } catch { /* */ }
}

// =====================================================
// ACTIONS
// =====================================================

async function triggerParse(priority?: string) {
  loading.value = true
  try {
    const { data } = await api.post('/news/parse', { priority })
    // Result will appear in live feed via WebSocket
  } catch { /* */ }
  loading.value = false
}

async function triggerRewriteBatch() {
  loading.value = true
  try {
    await api.post('/news/rewrite-batch')
  } catch { /* */ }
  loading.value = false
}

async function triggerPublish() {
  loading.value = true
  try {
    await api.post('/news/publish')
  } catch { /* */ }
  loading.value = false
}

async function approveArticle(id: number) {
  await api.post(`/news/articles/${id}/approve`)
  loadArticles()
}

async function rejectArticle(id: number) {
  await api.post(`/news/articles/${id}/reject`)
  loadArticles()
}

// =====================================================
// HELPERS
// =====================================================

function eventIcon(type: string) {
  const icons: Record<string, any> = {
    parsed: NewspaperIcon,
    rewritten: CheckCircleIcon,
    translated: LanguageIcon,
    published: RocketLaunchIcon,
    error: XCircleIcon,
    publish_error: ExclamationTriangleIcon,
  }
  return icons[type] || SignalIcon
}

function eventColor(type: string): string {
  const colors: Record<string, string> = {
    parsed: 'text-blue-500 bg-blue-50',
    rewritten: 'text-green-500 bg-green-50',
    translated: 'text-purple-500 bg-purple-50',
    published: 'text-emerald-600 bg-emerald-50',
    error: 'text-red-500 bg-red-50',
    publish_error: 'text-orange-500 bg-orange-50',
  }
  return colors[type] || 'text-gray-500 bg-gray-50'
}

function eventLabel(event: FeedEvent): string {
  switch (event.type) {
    case 'parsed': return `📥 Parsed from ${event.source}: "${event.title}"`
    case 'rewritten': return `✍️ Rewritten [${event.language}] plagiarism: ${event.plagiarism_score}%: "${event.title}"`
    case 'translated': return `🌐 Translated ${event.from_language}→${event.to_language}: "${event.title}"`
    case 'published': return `🚀 Published → ${event.target_site} [${event.language}]: "${event.title}"`
    case 'error': return `❌ Error [${event.source}]: ${event.message}`
    case 'publish_error': return `⚠️ Publish failed → ${event.site}: ${event.error}`
    default: return `${event.type}: ${event.title || event.message}`
  }
}

function statusBadge(status: string): string {
  const badges: Record<string, string> = {
    parsed: 'bg-blue-100 text-blue-700',
    rewritten: 'bg-green-100 text-green-700',
    needs_review: 'bg-yellow-100 text-yellow-700',
    published: 'bg-emerald-100 text-emerald-700',
    rejected: 'bg-red-100 text-red-600',
    skipped: 'bg-gray-100 text-gray-500',
    translating: 'bg-purple-100 text-purple-700',
    rewrite_failed: 'bg-red-100 text-red-600',
  }
  return badges[status] || 'bg-gray-100 text-gray-600'
}

function priorityDot(p: string): string {
  return { critical: '🔴', high: '🟠', medium: '🟡', low: '🟢' }[p] || '⚪'
}

function timeAgo(ts: string): string {
  const diff = Date.now() - new Date(ts).getTime()
  const mins = Math.floor(diff / 60000)
  if (mins < 1) return 'just now'
  if (mins < 60) return `${mins}m ago`
  const hours = Math.floor(mins / 60)
  if (hours < 24) return `${hours}h ago`
  return `${Math.floor(hours / 24)}d ago`
}

// =====================================================
// LIFECYCLE
// =====================================================

onMounted(() => {
  loadFeedHistory()
  loadArticles()
  loadStats()
  connectWebSocket()
})

onUnmounted(() => {
  if (echoChannel) echoChannel.stopListening('.news.event')
  if (pollInterval) clearInterval(pollInterval)
})
</script>

<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-bold text-gray-800">News Pipeline</h1>
        <span :class="['inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium', isConnected ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600']">
          <span :class="['w-2 h-2 rounded-full', isConnected ? 'bg-green-500 animate-pulse' : 'bg-red-500']" />
          {{ isConnected ? 'Live' : 'Offline' }}
        </span>
      </div>
      <div class="flex items-center gap-2">
        <button @click="triggerParse('critical')" :disabled="loading" class="px-3 py-2 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700 transition disabled:opacity-50">
          🔴 Parse Critical
        </button>
        <button @click="triggerParse()" :disabled="loading" class="px-3 py-2 text-xs bg-brand text-white rounded-lg hover:bg-brand-light transition disabled:opacity-50">
          Parse All
        </button>
        <button @click="triggerRewriteBatch()" :disabled="loading" class="px-3 py-2 text-xs bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition disabled:opacity-50">
          ✍️ Rewrite Batch
        </button>
        <button @click="triggerPublish()" :disabled="loading" class="px-3 py-2 text-xs bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition disabled:opacity-50">
          🚀 Publish
        </button>
      </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 bg-gray-100 rounded-lg p-1 w-fit">
      <button v-for="tab in (['feed', 'articles', 'stats', 'sources'] as const)" :key="tab"
        @click="activeTab = tab; if(tab==='articles') loadArticles(); if(tab==='stats') loadStats()"
        :class="['px-4 py-2 rounded-md text-sm font-medium transition', activeTab === tab ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700']">
        {{ tab === 'feed' ? '📡 Live Feed' : tab === 'articles' ? '📰 Articles' : tab === 'stats' ? '📊 Statistics' : '🔌 Sources' }}
      </button>
    </div>

    <!-- ======================================= -->
    <!-- TAB: LIVE FEED -->
    <!-- ======================================= -->
    <div v-if="activeTab === 'feed'" class="bg-gray-900 rounded-xl p-4 max-h-[70vh] overflow-y-auto font-mono text-sm">
      <div v-if="feedEvents.length === 0" class="text-gray-500 text-center py-12">
        Waiting for events... n8n automation will appear here in real-time.
      </div>
      <div v-for="(event, idx) in feedEvents" :key="idx" class="flex items-start gap-3 py-2 border-b border-gray-800 last:border-0">
        <span class="text-gray-600 text-xs whitespace-nowrap mt-0.5">{{ timeAgo(event.timestamp) }}</span>
        <div :class="['w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0', eventColor(event.type)]">
          <component :is="eventIcon(event.type)" class="w-3.5 h-3.5" />
        </div>
        <p :class="['flex-1', event.type === 'error' || event.type === 'publish_error' ? 'text-red-400' : 'text-gray-300']">
          {{ eventLabel(event) }}
        </p>
      </div>
    </div>

    <!-- ======================================= -->
    <!-- TAB: ARTICLES -->
    <!-- ======================================= -->
    <div v-if="activeTab === 'articles'">
      <!-- Filters -->
      <div class="flex flex-wrap gap-3 mb-4">
        <select v-model="articleFilter.status" @change="loadArticles()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
          <option value="">All statuses</option>
          <option v-for="s in ['parsed','rewritten','needs_review','published','rejected','skipped']" :key="s" :value="s">{{ s }}</option>
        </select>
        <select v-model="articleFilter.category" @change="loadArticles()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
          <option value="">All categories</option>
          <option v-for="c in ['poland_general','business','immigration','legal','eu_international','ukraine','technology','sport']" :key="c" :value="c">{{ c }}</option>
        </select>
        <select v-model="articleFilter.priority" @change="loadArticles()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
          <option value="">All priorities</option>
          <option v-for="p in ['critical','high','medium','low']" :key="p" :value="p">{{ p }}</option>
        </select>
      </div>

      <!-- Articles table -->
      <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-600">
            <tr>
              <th class="text-left px-4 py-3 font-medium">Title</th>
              <th class="text-left px-4 py-3 font-medium hidden md:table-cell">Source</th>
              <th class="text-left px-4 py-3 font-medium hidden lg:table-cell">Category</th>
              <th class="text-left px-4 py-3 font-medium">Status</th>
              <th class="text-left px-4 py-3 font-medium hidden lg:table-cell">Plagiarism</th>
              <th class="text-left px-4 py-3 font-medium">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="art in articles" :key="art.id" class="hover:bg-gray-50">
              <td class="px-4 py-3">
                <div class="font-medium text-gray-800">{{ priorityDot(art.priority) }} {{ art.rewritten_title || art.original_title }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ timeAgo(art.created_at) }}</div>
              </td>
              <td class="px-4 py-3 text-gray-500 hidden md:table-cell">{{ art.source_name }}</td>
              <td class="px-4 py-3 text-gray-500 hidden lg:table-cell capitalize">{{ art.category?.replace('_', ' ') }}</td>
              <td class="px-4 py-3">
                <span :class="['px-2 py-1 rounded-full text-xs font-medium', statusBadge(art.status)]">{{ art.status }}</span>
              </td>
              <td class="px-4 py-3 hidden lg:table-cell">
                <span v-if="art.plagiarism_score != null" :class="art.plagiarism_score < 15 ? 'text-green-600' : 'text-red-500'">
                  {{ art.plagiarism_score }}%
                </span>
                <span v-else class="text-gray-300">—</span>
              </td>
              <td class="px-4 py-3">
                <div class="flex gap-1">
                  <button v-if="art.status === 'needs_review'" @click="approveArticle(art.id)" class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200">✓</button>
                  <button v-if="['parsed','needs_review'].includes(art.status)" @click="rejectArticle(art.id)" class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded hover:bg-red-200">✗</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="loading" class="p-8 text-center text-gray-400">Loading...</div>
        <div v-else-if="!articles.length" class="p-8 text-center text-gray-400">No articles found</div>
      </div>
    </div>

    <!-- ======================================= -->
    <!-- TAB: STATISTICS -->
    <!-- ======================================= -->
    <div v-if="activeTab === 'stats' && stats" class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div class="bg-white rounded-xl p-5 border border-gray-100">
        <p class="text-sm text-gray-500">Total Parsed (7d)</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ stats.total_parsed?.toLocaleString() ?? 0 }}</p>
      </div>
      <div class="bg-white rounded-xl p-5 border border-gray-100">
        <p class="text-sm text-gray-500">Published</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ stats.by_status?.published ?? 0 }}</p>
      </div>
      <div class="bg-white rounded-xl p-5 border border-gray-100">
        <p class="text-sm text-gray-500">Needs Review</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ stats.by_status?.needs_review ?? 0 }}</p>
      </div>
      <div class="bg-white rounded-xl p-5 border border-gray-100">
        <p class="text-sm text-gray-500">Avg Plagiarism</p>
        <p :class="['text-2xl font-bold mt-1', (stats.avg_plagiarism_score ?? 0) < 15 ? 'text-green-600' : 'text-red-500']">
          {{ stats.avg_plagiarism_score ?? 0 }}%
        </p>
      </div>

      <!-- By Category -->
      <div class="col-span-2 bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">By Category</h3>
        <div v-for="cat in stats.by_category" :key="cat.category" class="flex justify-between text-sm py-1.5">
          <span class="text-gray-500 capitalize">{{ cat.category?.replace('_', ' ') }}</span>
          <span class="font-medium">{{ cat.count }}</span>
        </div>
      </div>

      <!-- By Source -->
      <div class="col-span-2 bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-4">Top Sources</h3>
        <div v-for="src in stats.by_source" :key="src.source_name" class="flex justify-between text-sm py-1.5">
          <span class="text-gray-500">{{ src.source_name }}</span>
          <span class="font-medium">{{ src.count }}</span>
        </div>
      </div>
    </div>

    <!-- ======================================= -->
    <!-- TAB: SOURCES -->
    <!-- ======================================= -->
    <div v-if="activeTab === 'sources'" class="text-sm text-gray-400 py-8 text-center">
      27 verified sources configured. View details in NewsSourcesRegistry.php.
    </div>
  </div>
</template>

<!--
Аннотация (RU):
NewsLiveFeedView.vue — страница News Pipeline в админ-панели.
4 вкладки: Live Feed (чат-лента), Articles (таблица), Statistics, Sources.

Live Feed: real-time WebSocket (Reverb) канал news-feed.
Типы событий: parsed (📥), rewritten (✍️), translated (🌐), published (🚀), error (❌).
Fallback: polling каждые 5 сек если Echo недоступен.
Dark theme (bg-gray-900), монопространственный шрифт.

Articles: таблица с фильтрами status/category/priority.
Approve/Reject кнопки для needs_review.
Plagiarism score с цветовой индикацией (<15% зелёный, >15% красный).

Statistics: total parsed, published, needs_review, avg plagiarism, by category, by source.

Action buttons: Parse Critical, Parse All, Rewrite Batch, Publish.
Файл: src/views/news/NewsLiveFeedView.vue
-->
