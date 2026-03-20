// =====================================================
// FILE: lib/screens/dashboard/dashboard_screen.dart
// Dashboard: KPI bar + section summary cards
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import '../providers/dashboard_provider.dart';
import '../providers/auth_provider.dart';
import '../models/models.dart';

class DashboardScreen extends ConsumerStatefulWidget {
  const DashboardScreen({super.key});

  @override
  ConsumerState<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends ConsumerState<DashboardScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(dashboardProvider.notifier).loadDashboard());
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(dashboardProvider);
    final theme = Theme.of(context);
    final plnFmt = NumberFormat.currency(locale: 'pl', symbol: 'PLN', decimalDigits: 0);

    return Scaffold(
      appBar: AppBar(
        title: const Text('WINCASE Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(dashboardProvider.notifier).loadDashboard(),
          ),
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () => ref.read(authProvider.notifier).logout(),
          ),
        ],
      ),
      body: state.isLoading && state.kpi == null
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: () => ref.read(dashboardProvider.notifier).loadDashboard(),
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  // ====== KPI GRID ======
                  if (state.kpi != null) ...[
                    Text('Key Metrics', style: theme.textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 12),
                    _KpiGrid(kpi: state.kpi!, plnFmt: plnFmt),
                    const SizedBox(height: 24),
                  ],

                  // ====== LEADS CARD ======
                  _SectionCard(
                    title: 'Leads',
                    icon: Icons.people,
                    color: Colors.blue,
                    children: [
                      _InfoRow('Total 30d', '${state.leads?['total_30d'] ?? 0}'),
                      _InfoRow('Today', '${state.leads?['today'] ?? 0}'),
                      _InfoRow('Unassigned', '${state.leads?['unassigned'] ?? 0}'),
                      _InfoRow('Conversion', '${state.leads?['conversion_rate'] ?? 0}%'),
                    ],
                  ),
                  const SizedBox(height: 12),

                  // ====== FINANCE CARD ======
                  _SectionCard(
                    title: 'Finance & POS',
                    icon: Icons.attach_money,
                    color: Colors.green,
                    children: [
                      _InfoRow('Monthly Revenue', plnFmt.format(state.finance?['monthly_revenue'] ?? 0)),
                      _InfoRow('POS Pending', '${state.finance?['pos']?['pending_count'] ?? 0}'),
                      _InfoRow('POS Approved', plnFmt.format(state.finance?['pos']?['approved_amount'] ?? 0)),
                    ],
                  ),
                  const SizedBox(height: 12),

                  // ====== ADS CARD ======
                  _SectionCard(
                    title: 'Ads Performance',
                    icon: Icons.campaign,
                    color: Colors.orange,
                    children: [
                      _InfoRow('Spend 7d', plnFmt.format(state.ads?['total_spend_7d'] ?? 0)),
                      _InfoRow('Leads 7d', '${state.ads?['total_leads_7d'] ?? 0}'),
                      _InfoRow('Budget Used', '${state.ads?['budget']?['pct_used'] ?? 0}%'),
                    ],
                  ),
                  const SizedBox(height: 12),

                  // ====== SOCIAL CARD ======
                  _SectionCard(
                    title: 'Social Media',
                    icon: Icons.share,
                    color: Colors.purple,
                    children: [
                      _InfoRow('Total Followers', '${state.social?['total_followers'] ?? 0}'),
                      _InfoRow('Posts 7d', '${state.social?['posts_last_7d'] ?? 0}'),
                      _InfoRow('Active Platforms', '${state.social?['platforms_active'] ?? 0}/8'),
                    ],
                  ),
                  const SizedBox(height: 12),

                  // ====== SEO CARD ======
                  _SectionCard(
                    title: 'SEO',
                    icon: Icons.search,
                    color: Colors.teal,
                    children: [
                      _InfoRow('GSC Clicks 7d', '${state.seo?['gsc_7d']?['clicks'] ?? 0}'),
                      _InfoRow('Organic Users 7d', '${state.seo?['ga4_7d']?['users'] ?? 0}'),
                      _InfoRow('Network Sites', '${state.seo?['network']?['active_sites'] ?? 0}'),
                    ],
                  ),
                  const SizedBox(height: 32),
                ],
              ),
            ),
    );
  }
}

// =====================================================
// KPI GRID WIDGET (3x4 cards)
// =====================================================

class _KpiGrid extends StatelessWidget {
  final KpiData kpi;
  final NumberFormat plnFmt;

  const _KpiGrid({required this.kpi, required this.plnFmt});

  @override
  Widget build(BuildContext context) {
    final items = [
      _KpiItem('Today Leads', '${kpi.todayLeads}', Icons.person_add, Colors.blue),
      _KpiItem('Active Cases', '${kpi.activeCases}', Icons.folder_open, Colors.indigo),
      _KpiItem('Revenue', plnFmt.format(kpi.monthlyRevenue), Icons.payments, Colors.green),
      _KpiItem('Avg Response', '${kpi.avgResponseMin.round()} min', Icons.timer, Colors.orange),
      _KpiItem('Ad Spend 7d', plnFmt.format(kpi.adSpend7d), Icons.campaign, Colors.red),
      _KpiItem('Organic Users', '${kpi.organicUsers7d}', Icons.search, Colors.teal),
      _KpiItem('Followers', '${kpi.socialFollowers}', Icons.people, Colors.purple),
      _KpiItem('Conv. Rate', '${kpi.conversionRate30d}%', Icons.trending_up, Colors.cyan),
      _KpiItem('Tasks Due', '${kpi.pendingTasks}', Icons.task_alt, Colors.amber),
      _KpiItem('Clients', '${kpi.activeClients}', Icons.business, Colors.blueGrey),
      _KpiItem('POS Pending', '${kpi.posPending}', Icons.point_of_sale, Colors.brown),
      _KpiItem('Tax Burden', plnFmt.format(kpi.monthlyTaxBurden), Icons.receipt_long, Colors.deepPurple),
    ];

    return GridView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 3,
        crossAxisSpacing: 8,
        mainAxisSpacing: 8,
        childAspectRatio: 1.1,
      ),
      itemCount: items.length,
      itemBuilder: (_, i) => _KpiCard(item: items[i]),
    );
  }
}

class _KpiItem {
  final String label;
  final String value;
  final IconData icon;
  final Color color;
  _KpiItem(this.label, this.value, this.icon, this.color);
}

class _KpiCard extends StatelessWidget {
  final _KpiItem item;
  const _KpiCard({required this.item});

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 1,
      child: Padding(
        padding: const EdgeInsets.all(8),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(item.icon, color: item.color, size: 22),
            const SizedBox(height: 4),
            Text(item.value,
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
              maxLines: 1, overflow: TextOverflow.ellipsis),
            const SizedBox(height: 2),
            Text(item.label,
              style: TextStyle(fontSize: 10, color: Colors.grey[600]),
              textAlign: TextAlign.center, maxLines: 1, overflow: TextOverflow.ellipsis),
          ],
        ),
      ),
    );
  }
}

// =====================================================
// SECTION CARD WIDGET
// =====================================================

class _SectionCard extends StatelessWidget {
  final String title;
  final IconData icon;
  final Color color;
  final List<Widget> children;

  const _SectionCard({
    required this.title,
    required this.icon,
    required this.color,
    required this.children,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 2,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(children: [
              Icon(icon, color: color, size: 24),
              const SizedBox(width: 8),
              Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
            ]),
            const Divider(),
            ...children,
          ],
        ),
      ),
    );
  }
}

class _InfoRow extends StatelessWidget {
  final String label;
  final String value;
  const _InfoRow(this.label, this.value);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(color: Colors.grey[700])),
          Text(value, style: const TextStyle(fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// DashboardScreen — главный экран приложения.
// KPI Grid (3x4): 12 карточек с иконками и значениями.
// 5 Section Cards: Leads, Finance/POS, Ads, Social, SEO.
// Pull-to-refresh → loadDashboard().
// Файл: lib/screens/dashboard/dashboard_screen.dart
// ---------------------------------------------------------------
