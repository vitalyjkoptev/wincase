import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../providers/boss_provider.dart';
import '../../main.dart';

class BossDashboardScreen extends ConsumerStatefulWidget {
  const BossDashboardScreen({super.key});
  @override
  ConsumerState<BossDashboardScreen> createState() => _BossDashboardScreenState();
}

class _BossDashboardScreenState extends ConsumerState<BossDashboardScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(bossDashboardProvider.notifier).load());
  }

  @override
  Widget build(BuildContext context) {
    final st = ref.watch(bossDashboardProvider);
    return Scaffold(
      backgroundColor: WC.background,
      body: st.isLoading
          ? const Center(child: CircularProgressIndicator())
          : st.error != null
              ? Center(child: Text(st.error!, style: const TextStyle(color: Colors.red)))
              : RefreshIndicator(
                  onRefresh: () => ref.read(bossDashboardProvider.notifier).load(),
                  child: _buildContent(st),
                ),
    );
  }

  Widget _buildContent(BossDashboardState st) {
    final stats = st.data?.stats;
    final user = st.data?.user;
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        // Welcome
        Container(
          padding: const EdgeInsets.all(20),
          decoration: BoxDecoration(
            gradient: const LinearGradient(colors: [WC.navy, WC.navyLight]),
            borderRadius: BorderRadius.circular(16),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Welcome back,', style: TextStyle(color: Colors.white.withValues(alpha: 0.7), fontSize: 14)),
              const SizedBox(height: 4),
              Text(user?.name ?? 'Director', style: const TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.bold)),
              const SizedBox(height: 12),
              Row(
                children: [
                  _miniStat(Icons.people, '${stats?.totalClients ?? 0}', 'Clients'),
                  const SizedBox(width: 20),
                  _miniStat(Icons.cases_outlined, '${stats?.activeCases ?? 0}', 'Cases'),
                  const SizedBox(width: 20),
                  _miniStat(Icons.group_work, '${stats?.workersOnline ?? 0}/${stats?.totalWorkers ?? 0}', 'Online'),
                ],
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // Revenue card
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: WC.border),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Monthly Revenue', style: TextStyle(fontSize: 12, color: WC.textSecondary)),
              const SizedBox(height: 4),
              Text(
                '${_fmtMoney(stats?.monthRevenue ?? 0)} PLN',
                style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: WC.navy),
              ),
              const SizedBox(height: 8),
              Row(
                children: [
                  _chip('Expenses: ${_fmtMoney(stats?.monthExpenses ?? 0)}', Colors.orange),
                  const SizedBox(width: 8),
                  _chip('Profit: ${_fmtMoney(stats?.profit ?? 0)}', WC.success),
                ],
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // 5 KPI Cards (admin panel style)
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            children: [
              _kpiCard('New Leads', '${stats?.newLeadsToday ?? 0}', Icons.person_add, WC.navy, '↑12%'),
              _kpiCard('Active Clients', '${stats?.totalClients ?? 0}', Icons.group, const Color(0xFF3B82F6), '↑8%'),
              _kpiCard('Open Cases', '${stats?.activeCases ?? 0}', Icons.work, const Color(0xFFFFC107), ''),
              _kpiCard('Tasks Due', '${stats?.tasksOverdue ?? 0}', Icons.task_alt, WC.info, '${stats?.tasksOverdue ?? 0} overdue'),
              _kpiCard('Revenue', '${_fmtMoney(stats?.monthRevenue ?? 0)}', Icons.euro, WC.danger, '↑18%'),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // Case Pipeline Mini
        _sectionTitle('Case Pipeline'),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            children: [
              _pipelineStage('Submitted', 8, WC.navy),
              _pipelineStage('Fingerprint', 5, const Color(0xFF3B82F6)),
              _pipelineStage('FP Appt', 4, WC.info),
              _pipelineStage('FP Done', 6, const Color(0xFF10B981)),
              _pipelineStage('Decision', 7, const Color(0xFFFFC107)),
              _pipelineStage('Signed', 3, Colors.orange),
              _pipelineStage('Card Issued', 1, WC.success),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // Quick stats grid
        GridView.count(
          crossAxisCount: 3,
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          mainAxisSpacing: 10,
          crossAxisSpacing: 10,
          childAspectRatio: 1.1,
          children: [
            _statCard('Unread', '${stats?.unreadMessages ?? 0}', Icons.chat_bubble, WC.info, stats?.unreadMessages != null && stats!.unreadMessages > 0),
            _statCard('Overdue', '${stats?.tasksOverdue ?? 0}', Icons.warning_amber, WC.danger, stats?.tasksOverdue != null && stats!.tasksOverdue > 0),
            _statCard('New Leads', '${stats?.newLeadsToday ?? 0}', Icons.person_add, const Color(0xFF3B82F6), false),
            _statCard('Expiring', '${stats?.expiringDocs ?? 0}', Icons.description, WC.warning, stats?.expiringDocs != null && stats!.expiringDocs > 0),
            _statCard('Pending', '${stats?.pendingPayments ?? 0}', Icons.payment, Colors.purple, false),
            _statCard('Conv.', '${(stats?.conversionRate ?? 0).toStringAsFixed(1)}%', Icons.trending_up, const Color(0xFF10B981), false),
          ],
        ),
        const SizedBox(height: 16),

        // Finance Quick Summary
        _sectionTitle('Finance Summary'),
        Row(
          children: [
            Expanded(child: _finCard('Payments', '${_fmtMoney(stats?.monthRevenue ?? 0)}', const Color(0xFF10B981))),
            const SizedBox(width: 8),
            Expanded(child: _finCard('Pending', '${stats?.pendingPayments ?? 0}', const Color(0xFFFFC107))),
          ],
        ),
        const SizedBox(height: 8),
        Row(
          children: [
            Expanded(child: _finCard('Overdue', '${stats?.tasksOverdue ?? 0}', WC.danger)),
            const SizedBox(width: 8),
            Expanded(child: _finCard('Expenses', '${_fmtMoney(stats?.monthExpenses ?? 0)}', WC.info)),
          ],
        ),
        const SizedBox(height: 16),

        // Quick Actions
        _sectionTitle('Quick Actions'),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            children: [
              _actionBtn(Icons.people, 'Clients', '/clients'),
              _actionBtn(Icons.work, 'Cases', '/cases'),
              _actionBtn(Icons.task_alt, 'Tasks', '/tasks'),
              _actionBtn(Icons.calendar_month, 'Calendar', '/calendar'),
              _actionBtn(Icons.description, 'Documents', '/documents'),
              _actionBtn(Icons.chat, 'Multichat', '/multichat'),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // Workers online
        if (st.data?.workers != null && st.data!.workers.isNotEmpty) ...[
          _sectionTitle('Workers Status'),
          ...st.data!.workers.take(5).map(_workerTile),
        ],

        // Critical cases
        if (st.data?.criticalCases != null && st.data!.criticalCases.isNotEmpty) ...[
          const SizedBox(height: 16),
          _sectionTitle('Critical Cases'),
          ...st.data!.criticalCases.take(5).map(_caseTile),
        ],

        const SizedBox(height: 80),
      ],
    );
  }

  Widget _miniStat(IconData icon, String val, String label) {
    return Row(
      children: [
        Icon(icon, color: Colors.white70, size: 16),
        const SizedBox(width: 4),
        Text(val, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14)),
        const SizedBox(width: 3),
        Text(label, style: TextStyle(color: Colors.white.withValues(alpha: 0.6), fontSize: 11)),
      ],
    );
  }

  Widget _chip(String text, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(color: color.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(12)),
      child: Text(text, style: TextStyle(fontSize: 11, color: color, fontWeight: FontWeight.w600)),
    );
  }

  Widget _statCard(String label, String value, IconData icon, Color color, bool alert) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: alert ? color.withValues(alpha: 0.5) : WC.border),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Row(
            children: [
              Icon(icon, color: color, size: 18),
              if (alert) ...[const SizedBox(width: 4), Container(width: 6, height: 6, decoration: BoxDecoration(color: WC.danger, shape: BoxShape.circle))],
            ],
          ),
          const Spacer(),
          Text(value, style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: color)),
          Text(label, style: const TextStyle(fontSize: 11, color: WC.textSecondary)),
        ],
      ),
    );
  }

  Widget _sectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Text(title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: WC.navy)),
    );
  }

  Widget _workerTile(worker) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: WC.border),
      ),
      child: Row(
        children: [
          CircleAvatar(
            radius: 18,
            backgroundColor: worker.isOnline ? WC.success.withValues(alpha: 0.15) : Colors.grey.withValues(alpha: 0.15),
            child: Text(worker.initials, style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: worker.isOnline ? WC.success : Colors.grey)),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text(worker.name, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                    const SizedBox(width: 6),
                    Container(
                      width: 8, height: 8,
                      decoration: BoxDecoration(color: worker.isOnline ? WC.success : Colors.grey, shape: BoxShape.circle),
                    ),
                  ],
                ),
                Text('${worker.activeClients} clients · ${worker.activeCases} cases', style: const TextStyle(fontSize: 11, color: WC.textSecondary)),
              ],
            ),
          ),
          if (worker.tasksOverdue > 0)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
              decoration: BoxDecoration(color: WC.danger.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(8)),
              child: Text('${worker.tasksOverdue} overdue', style: const TextStyle(fontSize: 10, color: WC.danger, fontWeight: FontWeight.w600)),
            ),
        ],
      ),
    );
  }

  Widget _caseTile(caseItem) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: WC.danger.withValues(alpha: 0.3)),
      ),
      child: Row(
        children: [
          const Icon(Icons.warning_amber, color: WC.danger, size: 20),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(caseItem.caseNumber, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                Text(caseItem.client?.name ?? caseItem.serviceLabel, style: const TextStyle(fontSize: 11, color: WC.textSecondary)),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
            decoration: BoxDecoration(color: WC.danger.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(8)),
            child: Text(caseItem.statusLabel, style: const TextStyle(fontSize: 10, color: WC.danger, fontWeight: FontWeight.w600)),
          ),
        ],
      ),
    );
  }

  Widget _kpiCard(String label, String value, IconData icon, Color color, String trend) {
    return Container(
      width: 120,
      margin: const EdgeInsets.only(right: 10),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: color.withOpacity(0.2)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: color, size: 18),
          const SizedBox(height: 6),
          Text(value, style: TextStyle(fontSize: 20, fontWeight: FontWeight.w800, color: color)),
          Text(label, style: const TextStyle(fontSize: 10, color: WC.textSecondary, fontWeight: FontWeight.w600)),
          if (trend.isNotEmpty)
            Text(trend, style: TextStyle(fontSize: 9, color: color, fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }

  Widget _pipelineStage(String label, int count, Color color) {
    return Container(
      margin: const EdgeInsets.only(right: 6),
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: color.withOpacity(0.3)),
      ),
      child: Column(
        children: [
          Text('$count', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800, color: color)),
          Text(label, style: const TextStyle(fontSize: 9, color: WC.textSecondary, fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }

  Widget _finCard(String label, String value, Color color) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: color.withOpacity(0.25)),
      ),
      child: Row(
        children: [
          Container(
            width: 32, height: 32,
            decoration: BoxDecoration(color: color.withOpacity(0.12), borderRadius: BorderRadius.circular(8)),
            child: Icon(Icons.euro, size: 16, color: color),
          ),
          const SizedBox(width: 10),
          Expanded(child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(value, style: TextStyle(fontSize: 14, fontWeight: FontWeight.w700, color: color)),
              Text(label, style: const TextStyle(fontSize: 10, color: WC.textSecondary)),
            ],
          )),
        ],
      ),
    );
  }

  Widget _actionBtn(IconData icon, String label, String route) {
    return GestureDetector(
      onTap: () => GoRouter.of(context).go(route),
      child: Container(
        width: 72,
        margin: const EdgeInsets.only(right: 8),
        padding: const EdgeInsets.symmetric(vertical: 10),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: WC.border),
        ),
        child: Column(
          children: [
            Container(
              width: 36, height: 36,
              decoration: BoxDecoration(color: WC.navy.withOpacity(0.08), borderRadius: BorderRadius.circular(8)),
              child: Icon(icon, size: 18, color: WC.navy),
            ),
            const SizedBox(height: 4),
            Text(label, style: const TextStyle(fontSize: 9, color: WC.textSecondary, fontWeight: FontWeight.w600)),
          ],
        ),
      ),
    );
  }

  String _fmtMoney(double v) {
    if (v >= 1000) return '${(v / 1000).toStringAsFixed(1)}k';
    return v.toStringAsFixed(0);
  }
}
