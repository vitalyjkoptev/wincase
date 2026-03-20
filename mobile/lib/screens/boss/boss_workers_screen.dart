import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../models/boss_models.dart';
import '../../providers/boss_provider.dart';
import '../../main.dart';

class BossWorkersScreen extends ConsumerStatefulWidget {
  const BossWorkersScreen({super.key});
  @override
  ConsumerState<BossWorkersScreen> createState() => _BossWorkersScreenState();
}

class _BossWorkersScreenState extends ConsumerState<BossWorkersScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(bossWorkersProvider.notifier).load());
  }

  @override
  Widget build(BuildContext context) {
    final st = ref.watch(bossWorkersProvider);
    return Scaffold(
      backgroundColor: WC.background,
      body: st.isLoading
          ? const Center(child: CircularProgressIndicator())
          : st.error != null
              ? Center(child: Text(st.error!, style: const TextStyle(color: Colors.red)))
              : RefreshIndicator(
                  onRefresh: () => ref.read(bossWorkersProvider.notifier).load(),
                  child: _buildList(st),
                ),
    );
  }

  Widget _buildList(WorkersState st) {
    final online = st.workers.where((w) => w.isOnline).toList();
    final offline = st.workers.where((w) => !w.isOnline).toList();

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        // Summary
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            gradient: const LinearGradient(colors: [WC.navy, WC.navyLight]),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _summaryItem('${st.workers.length}', 'Total'),
              Container(width: 1, height: 30, color: Colors.white24),
              _summaryItem('${online.length}', 'Online'),
              Container(width: 1, height: 30, color: Colors.white24),
              _summaryItem('${st.workers.fold(0, (s, w) => s + w.tasksOverdue)}', 'Overdue'),
            ],
          ),
        ),
        const SizedBox(height: 16),

        if (online.isNotEmpty) ...[
          _sectionLabel('Online Now', WC.success),
          ...online.map(_workerCard),
          const SizedBox(height: 12),
        ],
        if (offline.isNotEmpty) ...[
          _sectionLabel('Offline', Colors.grey),
          ...offline.map(_workerCard),
        ],
        const SizedBox(height: 80),
      ],
    );
  }

  Widget _summaryItem(String val, String label) {
    return Column(
      children: [
        Text(val, style: const TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.bold)),
        Text(label, style: TextStyle(color: Colors.white.withValues(alpha: 0.6), fontSize: 12)),
      ],
    );
  }

  Widget _sectionLabel(String text, Color color) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        children: [
          Container(width: 8, height: 8, decoration: BoxDecoration(color: color, shape: BoxShape.circle)),
          const SizedBox(width: 6),
          Text(text, style: TextStyle(fontSize: 13, fontWeight: FontWeight.bold, color: color)),
        ],
      ),
    );
  }

  Widget _workerCard(WorkerSummary w) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: WC.border),
      ),
      child: Column(
        children: [
          Row(
            children: [
              // Avatar
              Stack(
                children: [
                  CircleAvatar(
                    radius: 22,
                    backgroundColor: WC.navy.withValues(alpha: 0.1),
                    child: Text(w.initials, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: WC.navy)),
                  ),
                  Positioned(
                    bottom: 0, right: 0,
                    child: Container(
                      width: 12, height: 12,
                      decoration: BoxDecoration(
                        color: w.isOnline ? WC.success : Colors.grey,
                        shape: BoxShape.circle,
                        border: Border.all(color: Colors.white, width: 2),
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(w.name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                    if (w.position != null)
                      Text(w.position!, style: const TextStyle(fontSize: 11, color: WC.textSecondary)),
                  ],
                ),
              ),
              if (w.isClockedIn)
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                  decoration: BoxDecoration(color: WC.success.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(8)),
                  child: const Text('Clocked In', style: TextStyle(fontSize: 10, color: WC.success, fontWeight: FontWeight.w600)),
                ),
            ],
          ),
          const SizedBox(height: 10),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _metric(Icons.people, '${w.activeClients}', 'Clients'),
              _metric(Icons.cases_outlined, '${w.activeCases}', 'Cases'),
              _metric(Icons.warning_amber, '${w.tasksOverdue}', 'Overdue', alert: w.tasksOverdue > 0),
              _metric(Icons.chat_bubble_outline, '${w.unreadMessages}', 'Unread', alert: w.unreadMessages > 0),
            ],
          ),
        ],
      ),
    );
  }

  Widget _metric(IconData icon, String val, String label, {bool alert = false}) {
    final color = alert ? WC.danger : WC.textSecondary;
    return Column(
      children: [
        Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 14, color: color),
            const SizedBox(width: 3),
            Text(val, style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: alert ? WC.danger : WC.textPrimary)),
          ],
        ),
        Text(label, style: TextStyle(fontSize: 10, color: color)),
      ],
    );
  }
}
