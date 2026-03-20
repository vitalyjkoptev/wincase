// =====================================================
// FILE: lib/screens/pos/pos_detail_screen.dart
// POS transaction detail view
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import '../providers/pos_provider.dart';

class PosDetailScreen extends ConsumerStatefulWidget {
  final int transactionId;
  const PosDetailScreen({super.key, required this.transactionId});

  @override
  ConsumerState<PosDetailScreen> createState() => _PosDetailScreenState();
}

class _PosDetailScreenState extends ConsumerState<PosDetailScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(posProvider.notifier).loadDetail(widget.transactionId));
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(posProvider);
    final t = state.selected;
    final plnFmt = NumberFormat.currency(locale: 'pl', symbol: 'PLN');
    final dateFmt = DateFormat('dd.MM.yyyy HH:mm');

    return Scaffold(
      appBar: AppBar(title: Text(t?.receiptNumber ?? 'Transaction')),
      body: state.isLoading || t == null
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              padding: const EdgeInsets.all(16),
              children: [
                // Amount card
                Card(
                  color: Theme.of(context).colorScheme.primaryContainer,
                  child: Padding(
                    padding: const EdgeInsets.all(24),
                    child: Column(children: [
                      Text(plnFmt.format(t.totalAmount ?? t.amount),
                        style: const TextStyle(fontSize: 32, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 4),
                      Chip(label: Text(t.statusLabel)),
                    ]),
                  ),
                ),
                const SizedBox(height: 16),

                // Details
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(children: [
                      _Row('Receipt', t.receiptNumber),
                      _Row('Net Amount', plnFmt.format(t.amount)),
                      if (t.vatAmount != null) _Row('VAT 23%', plnFmt.format(t.vatAmount!)),
                      if (t.totalAmount != null) _Row('Total', plnFmt.format(t.totalAmount!)),
                      _Row('Method', t.methodLabel),
                      _Row('Status', t.statusLabel),
                      if (t.clientName != null) _Row('Client', t.clientName!),
                      if (t.clientPhone != null) _Row('Phone', t.clientPhone!),
                      if (t.serviceType != null) _Row('Service', t.serviceType!),
                      _Row('Created', dateFmt.format(t.createdAt)),
                      if (t.notes != null) _Row('Notes', t.notes!),
                    ]),
                  ),
                ),
                const SizedBox(height: 24),

                // Actions
                if (t.status == 'under_review') ...[
                  Row(children: [
                    Expanded(child: FilledButton.icon(
                      icon: const Icon(Icons.check),
                      label: const Text('Approve'),
                      onPressed: () async {
                        await ref.read(posProvider.notifier).approve(t.id);
                        if (mounted) Navigator.pop(context);
                      },
                    )),
                    const SizedBox(width: 12),
                    Expanded(child: OutlinedButton.icon(
                      icon: const Icon(Icons.close, color: Colors.red),
                      label: const Text('Reject', style: TextStyle(color: Colors.red)),
                      onPressed: () async {
                        await ref.read(posProvider.notifier).reject(t.id, 'Rejected from mobile');
                        if (mounted) Navigator.pop(context);
                      },
                    )),
                  ]),
                ],
              ],
            ),
    );
  }
}

class _Row extends StatelessWidget {
  final String label;
  final String value;
  const _Row(this.label, this.value);
  @override
  Widget build(BuildContext context) => Padding(
    padding: const EdgeInsets.symmetric(vertical: 6),
    child: Row(children: [
      SizedBox(width: 110, child: Text(label, style: TextStyle(color: Colors.grey[600]))),
      Expanded(child: Text(value, style: const TextStyle(fontWeight: FontWeight.w500))),
    ]),
  );
}

// ---------------------------------------------------------------
// Аннотация (RU):
// PosDetailScreen — детали POS транзакции.
// Amount card (крупный шрифт), status chip.
// Info: receipt, net, VAT, total, method, client, dates.
// Actions: Approve/Reject для status = under_review.
// Файл: lib/screens/pos/pos_detail_screen.dart
// ---------------------------------------------------------------
