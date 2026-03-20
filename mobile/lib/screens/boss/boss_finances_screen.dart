import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../providers/boss_provider.dart';
import '../../main.dart';

class BossFinancesScreen extends ConsumerStatefulWidget {
  const BossFinancesScreen({super.key});
  @override
  ConsumerState<BossFinancesScreen> createState() => _BossFinancesScreenState();
}

class _BossFinancesScreenState extends ConsumerState<BossFinancesScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(bossFinanceProvider.notifier).load());
  }

  @override
  Widget build(BuildContext context) {
    final st = ref.watch(bossFinanceProvider);
    return Scaffold(
      backgroundColor: WC.background,
      body: st.isLoading
          ? const Center(child: CircularProgressIndicator())
          : st.error != null
              ? Center(child: Text(st.error!, style: const TextStyle(color: Colors.red)))
              : RefreshIndicator(
                  onRefresh: () => ref.read(bossFinanceProvider.notifier).load(),
                  child: _buildContent(st),
                ),
    );
  }

  Widget _buildContent(FinanceState st) {
    final d = st.data;
    if (d == null) return const Center(child: Text('No data'));

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        // Revenue card
        Container(
          padding: const EdgeInsets.all(20),
          decoration: BoxDecoration(
            gradient: const LinearGradient(colors: [WC.navy, WC.navyLight]),
            borderRadius: BorderRadius.circular(16),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Total Revenue', style: TextStyle(color: Colors.white.withValues(alpha: 0.7), fontSize: 12)),
              const SizedBox(height: 4),
              Text('${_fmt(d.totalRevenue)} PLN', style: const TextStyle(color: Colors.white, fontSize: 28, fontWeight: FontWeight.bold)),
              const SizedBox(height: 16),
              Row(
                children: [
                  _miniCard('Expenses', '${_fmt(d.totalExpenses)}', Colors.orange),
                  const SizedBox(width: 12),
                  _miniCard('Net Profit', '${_fmt(d.netProfit)}', d.netProfit >= 0 ? WC.success : WC.danger),
                ],
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // Invoice summary
        Row(
          children: [
            Expanded(child: _statBox('Total', '${d.totalInvoices}', WC.navy)),
            const SizedBox(width: 10),
            Expanded(child: _statBox('Paid', '${d.paidInvoices}', WC.success)),
            const SizedBox(width: 10),
            Expanded(child: _statBox('Pending', '${d.pendingInvoices}', WC.warning)),
            const SizedBox(width: 10),
            Expanded(child: _statBox('Overdue', '${d.overdueInvoices}', WC.danger)),
          ],
        ),
        const SizedBox(height: 12),

        // Pending amount
        Container(
          padding: const EdgeInsets.all(14),
          decoration: BoxDecoration(
            color: WC.warning.withValues(alpha: 0.08),
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: WC.warning.withValues(alpha: 0.3)),
          ),
          child: Row(
            children: [
              const Icon(Icons.pending_actions, color: WC.warning, size: 28),
              const SizedBox(width: 12),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Pending Amount', style: TextStyle(fontSize: 12, color: WC.textSecondary)),
                  Text('${_fmt(d.pendingAmount)} PLN', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: WC.warning)),
                ],
              ),
            ],
          ),
        ),
        const SizedBox(height: 16),

        // Recent payments
        const Text('Recent Payments', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: WC.navy)),
        const SizedBox(height: 8),
        ...d.recentPayments.map(_paymentTile),
        const SizedBox(height: 80),
      ],
    );
  }

  Widget _miniCard(String label, String val, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(10),
        decoration: BoxDecoration(
          color: Colors.white.withValues(alpha: 0.12),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label, style: TextStyle(color: Colors.white.withValues(alpha: 0.6), fontSize: 11)),
            Text('$val PLN', style: TextStyle(color: color == WC.success ? const Color(0xFF80FF80) : Colors.orange.shade200, fontSize: 16, fontWeight: FontWeight.bold)),
          ],
        ),
      ),
    );
  }

  Widget _statBox(String label, String val, Color color) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(10),
        border: Border.all(color: color.withValues(alpha: 0.3)),
      ),
      child: Column(
        children: [
          Text(val, style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: color)),
          Text(label, style: const TextStyle(fontSize: 10, color: WC.textSecondary)),
        ],
      ),
    );
  }

  Widget _paymentTile(payment) {
    final statusColor = payment.status == 'paid' ? WC.success : payment.status == 'pending' ? WC.warning : WC.danger;
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
          Icon(
            payment.method == 'card' ? Icons.credit_card : payment.method == 'transfer' ? Icons.account_balance : Icons.money,
            color: WC.navy, size: 20,
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(payment.clientName, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                Text('${payment.method} · ${_fmtDate(payment.date)}', style: const TextStyle(fontSize: 11, color: WC.textSecondary)),
              ],
            ),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text('${payment.amount.toStringAsFixed(0)} PLN', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 1),
                decoration: BoxDecoration(color: statusColor.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(6)),
                child: Text(payment.status, style: TextStyle(fontSize: 10, color: statusColor, fontWeight: FontWeight.w600)),
              ),
            ],
          ),
        ],
      ),
    );
  }

  String _fmt(double v) {
    if (v >= 1000000) return '${(v / 1000000).toStringAsFixed(1)}M';
    if (v >= 1000) return '${(v / 1000).toStringAsFixed(1)}k';
    return v.toStringAsFixed(0);
  }

  String _fmtDate(DateTime dt) => '${dt.day}.${dt.month.toString().padLeft(2, '0')}';
}
