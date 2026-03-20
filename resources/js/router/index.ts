// =====================================================
// FILE: src/router/index.ts
// Vue Router — all views with RBAC navigation guard
// =====================================================

import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Lazy-loaded views
const Dashboard = () => import('@/views/DashboardView.vue')
const Leads = () => import('@/views/leads/LeadsView.vue')
const LeadDetail = () => import('@/views/leads/LeadDetailView.vue')
const Clients = () => import('@/views/clients/ClientsView.vue')
const ClientDetail = () => import('@/views/clients/ClientDetailView.vue')
const Cases = () => import('@/views/cases/CasesView.vue')
const CaseDetail = () => import('@/views/cases/CaseDetailView.vue')
const Tasks = () => import('@/views/tasks/TasksView.vue')
const Calendar = () => import('@/views/calendar/CalendarView.vue')
const POS = () => import('@/views/pos/POSView.vue')
const Accounting = () => import('@/views/accounting/AccountingView.vue')
const Ads = () => import('@/views/marketing/AdsView.vue')
const SEO = () => import('@/views/marketing/SEOView.vue')
const Social = () => import('@/views/marketing/SocialView.vue')
const Brand = () => import('@/views/marketing/BrandView.vue')
const Landings = () => import('@/views/marketing/LandingsView.vue')
const NewsPipeline = () => import('@/views/news/NewsLiveFeedView.vue')
const Reports = () => import('@/views/reports/ReportsView.vue')
const AuditLog = () => import('@/views/audit/AuditLogView.vue')
const SystemHealth = () => import('@/views/system/SystemHealthView.vue')
const Users = () => import('@/views/users/UsersView.vue')
const Settings = () => import('@/views/settings/SettingsView.vue')
const Documents = () => import('@/views/documents/DocumentsView.vue')
const Login = () => import('@/views/auth/LoginView.vue')
const NotFound = () => import('@/views/errors/NotFoundView.vue')

// =====================================================
// ROLE → ALLOWED ROUTES MAP
// =====================================================

const roleAccess: Record<string, string[]> = {
  admin: ['*'], // all routes
  manager: [
    'dashboard', 'leads', 'leads.detail', 'clients', 'clients.detail',
    'cases', 'cases.detail', 'tasks', 'calendar', 'pos', 'documents',
    'ads', 'seo', 'social', 'brand', 'landings', 'news', 'reports', 'settings',
  ],
  operator: [
    'dashboard', 'leads', 'leads.detail', 'clients', 'clients.detail',
    'cases', 'cases.detail', 'tasks', 'calendar', 'pos', 'documents', 'settings',
  ],
  accountant: [
    'dashboard', 'accounting', 'pos', 'reports', 'settings',
  ],
  viewer: [
    'dashboard', 'leads', 'clients', 'cases', 'tasks', 'calendar',
    'documents', 'reports', 'settings',
  ],
}

// =====================================================
// ROUTES
// =====================================================

const routes: RouteRecordRaw[] = [
  // Auth
  { path: '/login', name: 'login', component: Login, meta: { guest: true } },

  // Main layout routes
  {
    path: '/',
    redirect: '/dashboard',
    meta: { requiresAuth: true },
    children: [
      // Dashboard
      { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { title: 'Dashboard', icon: '📊' } },

      // Core CRM
      { path: '/leads', name: 'leads', component: Leads, meta: { title: 'Leads', icon: '👤' } },
      { path: '/leads/:id', name: 'leads.detail', component: LeadDetail, meta: { title: 'Lead Detail' } },
      { path: '/clients', name: 'clients', component: Clients, meta: { title: 'Clients', icon: '🏢' } },
      { path: '/clients/:id', name: 'clients.detail', component: ClientDetail, meta: { title: 'Client Detail' } },
      { path: '/cases', name: 'cases', component: Cases, meta: { title: 'Cases', icon: '📁' } },
      { path: '/cases/:id', name: 'cases.detail', component: CaseDetail, meta: { title: 'Case Detail' } },
      { path: '/tasks', name: 'tasks', component: Tasks, meta: { title: 'Tasks', icon: '📋' } },
      { path: '/calendar', name: 'calendar', component: Calendar, meta: { title: 'Calendar', icon: '📅' } },
      { path: '/documents', name: 'documents', component: Documents, meta: { title: 'Documents', icon: '📄' } },

      // Finance
      { path: '/pos', name: 'pos', component: POS, meta: { title: 'POS Terminal', icon: '💳' } },
      { path: '/accounting', name: 'accounting', component: Accounting, meta: { title: 'Accounting', icon: '💰', roles: ['admin', 'accountant'] } },

      // Marketing
      { path: '/ads', name: 'ads', component: Ads, meta: { title: 'Advertising', icon: '📢' } },
      { path: '/seo', name: 'seo', component: SEO, meta: { title: 'SEO', icon: '🔍' } },
      { path: '/social', name: 'social', component: Social, meta: { title: 'Social Media', icon: '📱' } },
      { path: '/brand', name: 'brand', component: Brand, meta: { title: 'Brand & Reputation', icon: '⭐' } },
      { path: '/landings', name: 'landings', component: Landings, meta: { title: 'Landing Pages', icon: '🌐' } },

      // Content
      { path: '/news', name: 'news', component: NewsPipeline, meta: { title: 'News Pipeline', icon: '📰' } },

      // Reports & System
      { path: '/reports', name: 'reports', component: Reports, meta: { title: 'Reports', icon: '📋' } },
      { path: '/audit', name: 'audit', component: AuditLog, meta: { title: 'Audit Log', icon: '🛡️', roles: ['admin'] } },
      { path: '/system', name: 'system', component: SystemHealth, meta: { title: 'System Health', icon: '🏥', roles: ['admin'] } },
      { path: '/users', name: 'users', component: Users, meta: { title: 'Users', icon: '👥', roles: ['admin'] } },
      { path: '/settings', name: 'settings', component: Settings, meta: { title: 'Settings', icon: '⚙️' } },
    ],
  },

  // 404
  { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound },
]

// =====================================================
// ROUTER INSTANCE
// =====================================================

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
})

// =====================================================
// NAVIGATION GUARD — Auth + RBAC
// =====================================================

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

  // Guest routes (login)
  if (to.meta.guest) {
    return auth.isAuthenticated ? next('/dashboard') : next()
  }

  // Auth required
  if (to.meta.requiresAuth || to.matched.some(r => r.meta.requiresAuth)) {
    if (!auth.isAuthenticated) {
      return next({ name: 'login', query: { redirect: to.fullPath } })
    }

    // RBAC check
    const routeName = to.name as string
    const userRole = auth.user?.role || 'viewer'

    // Admin bypasses all checks
    if (userRole === 'admin') return next()

    // Check route-level role restriction
    const allowedRoles = to.meta.roles as string[] | undefined
    if (allowedRoles && !allowedRoles.includes(userRole)) {
      return next('/dashboard')
    }

    // Check role access map
    const allowed = roleAccess[userRole] || []
    if (allowed.includes('*') || allowed.includes(routeName)) {
      return next()
    }

    // Default: redirect to dashboard
    return next('/dashboard')
  }

  next()
})

// =====================================================
// PAGE TITLE
// =====================================================

router.afterEach((to) => {
  const title = to.meta.title as string
  document.title = title ? `${title} — WinCase CRM` : 'WinCase CRM v4.0'
})

export default router

// =====================================================
// SIDEBAR NAVIGATION CONFIG (for AppSidebar.vue)
// =====================================================

export const sidebarNavigation = [
  { section: 'Main', items: [
    { name: 'dashboard', label: 'Dashboard', icon: '📊' },
  ]},
  { section: 'CRM', items: [
    { name: 'leads', label: 'Leads', icon: '👤', badge: 'unassigned' },
    { name: 'clients', label: 'Clients', icon: '🏢' },
    { name: 'cases', label: 'Cases', icon: '📁', badge: 'overdue' },
    { name: 'tasks', label: 'Tasks', icon: '📋', badge: 'my_tasks' },
    { name: 'calendar', label: 'Calendar', icon: '📅' },
    { name: 'documents', label: 'Documents', icon: '📄' },
  ]},
  { section: 'Finance', items: [
    { name: 'pos', label: 'POS Terminal', icon: '💳' },
    { name: 'accounting', label: 'Accounting', icon: '💰', roles: ['admin', 'accountant'] },
  ]},
  { section: 'Marketing', items: [
    { name: 'ads', label: 'Advertising', icon: '📢' },
    { name: 'seo', label: 'SEO', icon: '🔍' },
    { name: 'social', label: 'Social Media', icon: '📱' },
    { name: 'brand', label: 'Brand', icon: '⭐' },
    { name: 'landings', label: 'Landings', icon: '🌐' },
  ]},
  { section: 'Content', items: [
    { name: 'news', label: 'News Pipeline', icon: '📰' },
  ]},
  { section: 'Analytics', items: [
    { name: 'reports', label: 'Reports', icon: '📋' },
  ]},
  { section: 'Admin', items: [
    { name: 'users', label: 'Users', icon: '👥', roles: ['admin'] },
    { name: 'audit', label: 'Audit Log', icon: '🛡️', roles: ['admin'] },
    { name: 'system', label: 'System', icon: '🏥', roles: ['admin'] },
    { name: 'settings', label: 'Settings', icon: '⚙️' },
  ]},
]

// ---------------------------------------------------------------
// Аннотация (RU):
// Vue Router — 25+ маршрутов с lazy loading + RBAC navigation guard.
// 5 ролей: admin (*), manager (CRM+marketing+news), operator (CRM+POS),
// accountant (dashboard+accounting+POS+reports), viewer (read-only).
// Navigation guard: auth check → role check → route-level roles → roleAccess map.
// Sidebar: 7 секций (Main, CRM, Finance, Marketing, Content, Analytics, Admin).
// Badges: unassigned leads, overdue cases, my tasks.
// Page titles: "{title} — WinCase CRM".
// Файл: src/router/index.ts
// ---------------------------------------------------------------
