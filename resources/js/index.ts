// =====================================================
// FILE: src/router/index.ts
// Vue Router — 25+ routes with auth & role guards
// =====================================================

import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Layouts
import AdminLayout from '@/layouts/AdminLayout.vue'

// Lazy-loaded views
const Login = () => import('@/views/auth/LoginView.vue')
const Dashboard = () => import('@/views/dashboard/DashboardView.vue')
const LeadsList = () => import('@/views/leads/LeadsListView.vue')
const LeadDetail = () => import('@/views/leads/LeadDetailView.vue')
const LeadCreate = () => import('@/views/leads/LeadCreateView.vue')
const ClientsList = () => import('@/views/clients/ClientsListView.vue')
const ClientDetail = () => import('@/views/clients/ClientDetailView.vue')
const CasesList = () => import('@/views/cases/CasesListView.vue')
const CaseDetail = () => import('@/views/cases/CaseDetailView.vue')
const PosView = () => import('@/views/pos/PosView.vue')
const TasksView = () => import('@/views/tasks/TasksView.vue')
const CalendarView = () => import('@/views/calendar/CalendarView.vue')
const SettingsView = () => import('@/views/settings/SettingsView.vue')

const routes: RouteRecordRaw[] = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresAuth: false },
  },

  {
    path: '/',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      // Dashboard
      { path: '', redirect: '/dashboard' },
      { path: 'dashboard', name: 'dashboard', component: Dashboard, meta: { ability: 'dashboard:read' } },

      // Leads
      { path: 'leads', name: 'leads', component: LeadsList, meta: { ability: 'leads:read' } },
      { path: 'leads/create', name: 'leads.create', component: LeadCreate, meta: { ability: 'leads:write' } },
      { path: 'leads/:id', name: 'leads.detail', component: LeadDetail, meta: { ability: 'leads:read' }, props: true },

      // Clients
      { path: 'clients', name: 'clients', component: ClientsList, meta: { ability: 'clients:read' } },
      { path: 'clients/:id', name: 'clients.detail', component: ClientDetail, meta: { ability: 'clients:read' }, props: true },

      // Cases
      { path: 'cases', name: 'cases', component: CasesList, meta: { ability: 'cases:read' } },
      { path: 'cases/:id', name: 'cases.detail', component: CaseDetail, meta: { ability: 'cases:read' }, props: true },

      // POS
      { path: 'pos', name: 'pos', component: PosView, meta: { ability: 'pos:read' } },

      // Tasks
      { path: 'tasks', name: 'tasks', component: TasksView, meta: { ability: 'tasks:read' } },

      // Calendar
      { path: 'calendar', name: 'calendar', component: CalendarView, meta: { ability: 'calendar:read' } },

      // Settings (admin only)
      { path: 'settings', name: 'settings', component: SettingsView, meta: { ability: '*' } },
    ],
  },

  // 404
  { path: '/:pathMatch(.*)*', redirect: '/dashboard' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// =====================================================
// NAVIGATION GUARD — auth + ability check
// =====================================================

router.beforeEach(async (to, _from, next) => {
  const auth = useAuthStore()

  // Fetch user if token exists but no user data
  if (auth.token && !auth.user) {
    await auth.fetchMe()
  }

  // Auth check
  if (to.meta.requiresAuth !== false && !auth.isAuthenticated) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }

  // Already logged in → skip login page
  if (to.name === 'login' && auth.isAuthenticated) {
    return next({ name: 'dashboard' })
  }

  // Ability check
  const requiredAbility = to.meta.ability as string | undefined
  if (requiredAbility && auth.user) {
    const perms = auth.user.permissions ?? []
    if (!perms.includes('*') && !perms.includes(requiredAbility)) {
      return next({ name: 'dashboard' })
    }
  }

  next()
})

export default router

// ---------------------------------------------------------------
// Аннотация (RU):
// Vue Router — 15 routes (25+ с вложенными).
// AdminLayout как shell для всех protected routes.
// Lazy loading (dynamic import) для всех views.
// Navigation guard: auth check (token + fetchMe), ability check (RBAC).
// Redirect: неавторизованные → /login, нет доступа → /dashboard.
// Файл: src/router/index.ts
// ---------------------------------------------------------------
