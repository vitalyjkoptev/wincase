<script setup lang="ts">
// =====================================================
// FILE: src/views/news/NewsLiveFeedV2.vue
// Live Feed Chat + Pipeline Dashboard + Site Stats
// =====================================================

import { ref, onMounted, onUnmounted, nextTick, computed } from 'vue'
import api from '@/composables/useApi'
import Echo from 'laravel-echo'

// =====================================================
// STATE
// =====================================================

const activeTab = ref<'feed' | 'articles' | 'sites' | 'stats'>('feed')
const feedItems = ref<any[]>([])
const articles = ref<any[]>([])
const sites = ref<any>(null)
const stats = ref<any>(null)
const loading = ref(false)
const feedContainer = ref<HTMLElement | null>(null)
const filterAction = ref('')
const autoScroll = ref(true)
const connected = ref(false)
let echoChannel: any = null

// Article filters
const artFilter = ref({ status: '', search: '', site: '' })

// =====================================================
// LIVE FEED — WebSocket
// =====================================================

function connectWebSocket() {
  try {
    const echo = (window as any).Echo as Echo
    if (!echo) return

    echoChannel = echo.channel('news-feed')
      .listen('.feed.update', (event: any) => {
        feedItems.value.unshift({
          ...event,
          _local_time: new Date().toLocaleTimeString('pl-PL', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
        })
        // Keep max 500 items
        if (feedItems.value.length > 500) feedItems.value = feedItems.value.slice(0, 500)
        if (autoScroll.value) nextTick(() => scrollToTop())
      })

    connected.value = true
  } catch (e) {
    console.warn('WebSocket connection failed:', e)
    connected.value = false
  }
}

function scrollToTop() {
  feedContainer.value?.scrollTo({ top: 0, behavior: 'smooth' })
}

// =====================================================
// DATA LOADING
// =====================================================

async function loadFeedHistory() {
  try {
    const { data } = await api.get('/news/feed', { params: { limit: 200 } })
    feedItems.value = (data.data ?? []).map((item: any) => ({
      ...item,
      _local_time: new Date(item.created_at).toLocaleTimeString('pl-PL', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
    }))
  } catch { /* */ }
}

async function loadArticles() {
  loading.value = true
  try {
    const params: any = { per_page: 50 }
    if (artFilter.value.status) params.status = artFilter.value.status
    if (artFilter.value.search) params.search = artFilter.value.search
    if (artFilter.value.site) params.site = artFilter.value.site
    const { data } = await api.get('/news/articles', { params })
    articles.value = data.data?.data ?? []
  } catch { /* */ }
  loading.value = false
}

async function loadSites() {
  try {
    const { data } = await api.get('/news/sites')
    sites.value = data.data
  } catch { /* */ }
}

async function loadStats() {
  try {
    const { data } = await api.get('/news/statistics')
    stats.value = data.data
  } catch { /* */ }
}

// =====================================================
// ACTIONS
// =====================================================

async function triggerRewriteBatch() {
  await api.post('/news/rewrite-batch')
}
async function triggerPublishAll() {
  await api.post('/news/publish')
}
async function publishSingle(id: number) {
  await api.post(`/news/publish/${id}`)
  loadArticles()
}
async function rewriteSingle(id: number) {
  await api.post(`/news/rewrite/${id}`)
  loadArticles()
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

function actionIcon(action: string): string {
  const icons: Record<string, string> = {
    parsed: '📥', rewrite_start: '🔄', rewrite_done: '✅', rewrite_retry: '⚠️',
    rewrite_retry_3: '🔴', rewrite_failed: '❌', published: '📤', publish_failed: '💥',
    priority_publish: '⚡', batch_rewrite: '🔄', batch_publish: '📤', error: '❌',
  }
  return icons[action] || '📝'
}

function actionColor(action: string): string {
  if (action.includes('failed') || action === 'error') return 'border-l-red-500 bg-red-50/50'
  if (action.includes('retry')) return 'border-l-yellow-500 bg-yellow-50/50'
  if (action === 'published' || action === 'rewrite_done') return 'border-l-green-500 bg-green-50/50'
  if (action === 'priority_publish') return 'border-l-purple-500 bg-purple-50/50'
  if (action.includes('batch')) return 'border-l-blue-500 bg-blue-50/50'
  return 'border-l-gray-300 bg-gray-50/30'
}

function statusBadge(status: string): string {
  const map: Record<string, string> = {
    parsed: 'bg-blue-100 text-blue-700',
    rewritten: 'bg-green-100 text-green-700',
    published: 'bg-emerald-100 text-emerald-700',
    partial: 'bg-yellow-100 text-yellow-700',
    needs_review: 'bg-orange-100 text-orange-700',
    rejected: 'bg-red-100 text-red-700',
    rewrite_failed: 'bg-red-100 text-red-700',
  }
  return map[status] || 'bg-gray-100 text-gray-600'
}

const filteredFeed = computed(() => {
  if (!filterAction.value) return feedItems.value
  return feedItems.value.filter(i => i.action === filterAction.value)
})

// =====================================================
// LIFECYCLE
// =====================================================

onMounted(() => {
  loadFeedHistory()
  loadSites()
  loadStats()
  loadArticles()
  connectWebSocket()
})

onUnmounted(() => {
  if (echoChannel) echoChannel.stopListening('.feed.update')
})
</script>

<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-bold text-gray-800">📰 News Pipeline</h1>
        <span :class="['w-2.5 h-2.5 rounded-full', connected ? 'bg-green-500 animate-pulse' : 'bg-red-500']" />
        <span class="text-xs text-gray-400">{{ connected ? 'LIVE' : 'OFFLINE' }}</span>
      </div>
      <div class="flex gap-2">
        <button @click="triggerRewriteBatch" class="px-3 py-1.5 text-xs bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">🔄 Rewrite Batch</button>
        <button @click="triggerPublishAll" class="px-3 py-1.5 text-xs bg-green-100 text-green-700 rounded-lg hover:bg-green-200">📤 Publish Ready</button>
      </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 mb-5 bg-gray-100 rounded-lg p-1 w-fit">
      <button v-for="tab in (['feed', 'articles', 'sites', 'stats'] as const)" :key="tab"
        @click="activeTab = tab"
        :class="['px-4 py-2 rounded-md text-sm font-medium transition', activeTab === tab ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500']">
        {{ tab === 'feed' ? '💬 Live Feed' : tab === 'articles' ? '📄 Articles' : tab === 'sites' ? '🌐 Sites' : '📊 Stats' }}
      </button>
    </div>

    <!-- ============================== -->
    <!-- TAB: LIVE FEED (chat style) -->
    <!-- ============================== -->
    <div v-if="activeTab === 'feed'">
      <!-- Feed filter -->
      <div class="flex items-center gap-2 mb-3">
        <select v-model="filterAction" class="px-2 py-1 text-xs border border-gray-200 rounded-lg">
          <option value="">All events</option>
          <option value="parsed">📥 Parsed</option>
          <option value="rewrite_done">✅ Rewritten</option>
          <option value="published">📤 Published</option>
          <option value="rewrite_failed">❌ Failed</option>
          <option value="priority_publish">⚡ Breaking</option>
        </select>
        <label class="flex items-center gap-1 text-xs text-gray-400">
          <input type="checkbox" v-model="autoScroll" class="w-3 h-3" /> Auto-scroll
        </label>
        <span class="text-xs text-gray-400 ml-auto">{{ feedItems.length }} events</span>
      </div>

      <!-- Feed container (chat-style) -->
      <div ref="feedContainer" class="bg-gray-900 rounded-xl p-4 h-[600px] overflow-y-auto space-y-2 font-mono text-sm">
        <div v-if="!filteredFeed.length" class="text-gray-500 text-center py-20">
          Waiting for events...
        </div>

        <div v-for="item in filteredFeed" :key="item.id"
          :class="['border-l-4 rounded-r-lg px-3 py-2 transition-all', actionColor(item.action)]">
          <div class="flex items-center gap-2">
            <span class="text-base">{{ actionIcon(item.action) }}</span>
            <span class="text-gray-300 text-xs font-bold">{{ item._local_time }}</span>
            <span class="text-gray-500 text-xs">[{{ item.action }}]</span>
          </div>
          <div class="text-gray-100 text-xs mt-1 leading-relaxed">{{ item.message }}</div>
          <div v-if="item.meta?.site" class="flex gap-2 mt-1">
            <span class="text-xs px-1.5 py-0.5 bg-white/10 rounded text-gray-400">{{ item.meta.site }}</span>
            <span v-if="item.meta.category" class="text-xs px-1.5 py-0.5 bg-white/10 rounded text-gray-400">{{ item.meta.category }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================== -->
    <!-- TAB: ARTICLES -->
    <!-- ============================== -->
    <div v-if="activeTab === 'articles'">
      <!-- Filters -->
      <div class="flex flex-wrap gap-2 mb-4">
        <select v-model="artFilter.status" @change="loadArticles()" class="px-3 py-1.5 text-sm border rounded-lg">
          <option value="">All statuses</option>
          <option v-for="s in ['parsed','rewritten','published','partial','needs_review','rejected','rewrite_failed']" :key="s" :value="s">{{ s }}</option>
        </select>
        <select v-model="artFilter.site" @change="loadArticles()" class="px-3 py-1.5 text-sm border rounded-lg">
          <option value="">All sites</option>
          <option v-for="(site, key) in (sites?.sites ?? {})" :key="key" :value="key">{{ site.name }}</option>
        </select>
        <input v-model="artFilter.search" @keyup.enter="loadArticles()" placeholder="Search..." class="px-3 py-1.5 text-sm border rounded-lg w-48" />
      </div>

      <!-- Articles table -->
      <div class="bg-white rounded-xl border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-500">
            <tr>
              <th class="text-left px-4 py-3">Title</th>
              <th class="text-left px-3 py-3 w-24">Status</th>
              <th class="text-left px-3 py-3 w-20">Plag %</th>
              <th class="text-left px-3 py-3 w-28">Source</th>
              <th class="text-left px-3 py-3 w-24">Sites</th>
              <th class="text-left px-3 py-3 w-28">Time</th>
              <th class="text-right px-4 py-3 w-36">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="a in articles" :key="a.id" class="border-t border-gray-50 hover:bg-gray-50/50">
              <td class="px-4 py-2.5">
                <div class="font-medium text-gray-800 truncate max-w-sm">{{ a.rewritten_title || a.original_title }}</div>
              </td>
              <td class="px-3 py-2.5">
                <span :class="['text-xs font-medium px-2 py-0.5 rounded-full', statusBadge(a.status)]">{{ a.status }}</span>
              </td>
              <td class="px-3 py-2.5">
                <span :class="['text-xs font-bold', (a.plagiarism_score ?? 0) < 15 ? 'text-green-600' : (a.plagiarism_score ?? 0) < 30 ? 'text-yellow-600' : 'text-red-600']">
                  {{ a.plagiarism_score ?? '—' }}%
                </span>
              </td>
              <td class="px-3 py-2.5 text-xs text-gray-500 truncate max-w-[120px]">{{ a.source_name }}</td>
              <td class="px-3 py-2.5">
                <div class="flex gap-0.5 flex-wrap">
                  <span v-for="site in (a.published_sites || [])" :key="site" class="text-[10px] px-1 py-0.5 bg-emerald-100 text-emerald-700 rounded">
                    {{ site.split(':')[0] }}
                  </span>
                </div>
              </td>
              <td class="px-3 py-2.5 text-xs text-gray-400">{{ new Date(a.parsed_at).toLocaleString('pl-PL', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }) }}</td>
              <td class="px-4 py-2.5 text-right">
                <div class="flex gap-1 justify-end">
                  <button v-if="a.status === 'parsed'" @click="rewriteSingle(a.id)" class="text-[10px] px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">🔄</button>
                  <button v-if="a.status === 'needs_review'" @click="approveArticle(a.id)" class="text-[10px] px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200">✅</button>
                  <button v-if="a.status === 'rewritten'" @click="publishSingle(a.id)" class="text-[10px] px-2 py-1 bg-emerald-100 text-emerald-700 rounded hover:bg-emerald-200">📤</button>
                  <button v-if="!['rejected','published'].includes(a.status)" @click="rejectArticle(a.id)" class="text-[10px] px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">✗</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="loading" class="p-8 text-center text-gray-400">Loading...</div>
        <div v-else-if="!articles.length" class="p-8 text-center text-gray-400">No articles</div>
      </div>
    </div>

    <!-- ============================== -->
    <!-- TAB: SITES -->
    <!-- ============================== -->
    <div v-if="activeTab === 'sites' && sites" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="(site, key) in sites.sites" :key="key"
        class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-sm transition">
        <div class="flex items-center justify-between mb-3">
          <h3 class="font-bold text-gray-800">{{ site.name }}</h3>
          <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full">{{ site.type }}</span>
        </div>
        <p class="text-sm text-gray-500 mb-3">{{ site.domain }}</p>
        <div class="flex flex-wrap gap-1.5">
          <span v-for="(cat, catKey) in site.categories" :key="catKey"
            class="text-[10px] px-2 py-1 bg-gray-100 text-gray-600 rounded-full">
            {{ cat.label_en }}
          </span>
        </div>
        <div class="flex gap-2 mt-3">
          <span v-for="lang in site.languages" :key="lang"
            class="text-[10px] px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded font-medium uppercase">
            {{ lang }}
          </span>
        </div>
        <div v-if="stats?.by_site" class="mt-3 text-sm">
          <span class="text-gray-400">Published: </span>
          <span class="font-bold text-gray-700">{{ stats.by_site[key] ?? 0 }}</span>
        </div>
      </div>
    </div>

    <!-- ============================== -->
    <!-- TAB: STATS -->
    <!-- ============================== -->
    <div v-if="activeTab === 'stats' && stats" class="space-y-6">
      <!-- KPI cards -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
          <p class="text-2xl font-bold text-gray-800">{{ stats.total_articles?.toLocaleString() }}</p>
          <p class="text-xs text-gray-400">Total Articles</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
          <p class="text-2xl font-bold text-blue-600">{{ stats.today_parsed }}</p>
          <p class="text-xs text-gray-400">Parsed Today</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
          <p class="text-2xl font-bold text-green-600">{{ stats.today_published }}</p>
          <p class="text-xs text-gray-400">Published Today</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
          <p class="text-2xl font-bold" :class="(stats.avg_plagiarism ?? 0) < 15 ? 'text-green-600' : 'text-yellow-600'">{{ stats.avg_plagiarism }}%</p>
          <p class="text-xs text-gray-400">Avg Plagiarism</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
          <p class="text-2xl font-bold text-purple-600">{{ stats.sources?.total_sources }}</p>
          <p class="text-xs text-gray-400">RSS Sources</p>
        </div>
      </div>

      <!-- By status -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-3">By Status</h3>
        <div class="flex flex-wrap gap-3">
          <div v-for="s in stats.by_status" :key="s.status" class="flex items-center gap-2">
            <span :class="['text-xs font-medium px-2 py-0.5 rounded-full', statusBadge(s.status)]">{{ s.status }}</span>
            <span class="font-bold text-gray-700">{{ s.count }}</span>
          </div>
        </div>
      </div>

      <!-- By site -->
      <div class="bg-white rounded-xl p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-700 mb-3">Published by Site</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
          <div v-for="(count, site) in stats.by_site" :key="site" class="text-center p-3 bg-gray-50 rounded-lg">
            <p class="text-lg font-bold text-gray-800">{{ count }}</p>
            <p class="text-xs text-gray-400">{{ site }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Custom scrollbar for dark feed */
.font-mono::-webkit-scrollbar { width: 6px; }
.font-mono::-webkit-scrollbar-track { background: #1a1a2e; }
.font-mono::-webkit-scrollbar-thumb { background: #4a4a6a; border-radius: 3px; }
</style>

<!--
Аннотация (RU):
NewsLiveFeedV2.vue — главная страница News Pipeline в админке.
4 вкладки: Live Feed, Articles, Sites, Stats.

💬 Live Feed: тёмный chat-стиль на всю высоту (600px).
WebSocket канал 'news-feed' → event '.feed.update' → prepend в ленту.
Каждое событие: icon + timestamp + [action] + message + site/category badges.
Цветовая кодировка: зелёный (success), красный (error), жёлтый (retry), фиолетовый (breaking).
Фильтр по action, auto-scroll toggle.

📄 Articles: таблица с фильтрами (status, site, search).
Кнопки: rewrite, approve, publish, reject. Plagiarism % цветной.

🌐 Sites: 8 карточек с категориями, языками, published count.

📊 Stats: 5 KPI cards, by status, by site.
Файл: src/views/news/NewsLiveFeedV2.vue
-->
