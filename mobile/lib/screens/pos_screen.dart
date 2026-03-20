// =====================================================
// FILE: lib/screens/pos/pos_screen.dart
// POS: pending list, receive payment, approve/reject
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';
import '../providers/pos_provider.dart';
import '../models/models.dart';

class PosScreen extends ConsumerStatefulWidget {
  const PosScreen({super.key});

  @override
  ConsumerState<PosScreen> createState() => _PosScreenState();
}

class _PosScreenState extends ConsumerState<PosScreen>
    with SingleTickerProviderStateMixin {
  late TabController _tabCtrl;

  @override
  void initState() {
    super.initState();
    _tabCtrl = TabController(length: 2, vsync: this);
    Future.microtask(() => ref.read(posProvider.notifier).loadTransactions());
  }

  @override
  void dispose() {
    _tabCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(posProvider);
    final pending = state.transactions.where((t) => t.isPending).toList();
    final completed = state.transactions.where((t) => !t.isPending).toList();

    return Scaffold(
      appBar: AppBar(
        title: Text('POS Terminal (${state.pendingCount} pending)'),
        bottom: TabBar(
          controller: _tabCtrl,
          tabs: const [Tab(text: 'Pending'), Tab(text: 'Completed')],
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(posProvider.notifier).loadTransactions(),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showReceiveDialog(context),
        icon: const Icon(Icons.add),
        label: const Text('Receive'),
      ),
      body:
          state.isLoading && state.transactions.isEmpty
              ? const Center(child: CircularProgressIndicator())
              : TabBarView(
                controller: _tabCtrl,
                children: [
                  _TransactionList(transactions: pending, showActions: true),
                  _TransactionList(transactions: completed, showActions: false),
                ],
              ),
    );
  }

  void _showReceiveDialog(BuildContext context) {
    final amountCtrl = TextEditingController();
    final nameCtrl = TextEditingController();
    final phoneCtrl = TextEditingController();
    String method = 'cash';

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder:
          (ctx) => StatefulBuilder(
            builder:
                (ctx, setSheetState) => Padding(
                  padding: EdgeInsets.fromLTRB(
                    16,
                    16,
                    16,
                    MediaQuery.of(ctx).viewInsets.bottom + 16,
                  ),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Receive Payment',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 16),
                      TextField(
                        controller: amountCtrl,
                        keyboardType: const TextInputType.numberWithOptions(
                          decimal: true,
                        ),
                        decoration: const InputDecoration(
                          labelText: 'Amount (PLN) *',
                          border: OutlineInputBorder(),
                        ),
                      ),
                      const SizedBox(height: 12),
                      TextField(
                        controller: nameCtrl,
                        decoration: const InputDecoration(
                          labelText: 'Client Name',
                          border: OutlineInputBorder(),
                        ),
                      ),
                      const SizedBox(height: 12),
                      TextField(
                        controller: phoneCtrl,
                        keyboardType: TextInputType.phone,
                        decoration: const InputDecoration(
                          labelText: 'Client Phone',
                          border: OutlineInputBorder(),
                        ),
                      ),
                      const SizedBox(height: 12),
                      DropdownButtonFormField<String>(
                        value: method,
                        decoration: const InputDecoration(
                          labelText: 'Payment Method',
                          border: OutlineInputBorder(),
                        ),
                        items: const [
                          DropdownMenuItem(value: 'cash', child: Text('Cash')),
                          DropdownMenuItem(
                            value: 'card',
                            child: Text('Card (Terminal)'),
                          ),
                          DropdownMenuItem(value: 'blik', child: Text('BLIK')),
                          DropdownMenuItem(
                            value: 'transfer',
                            child: Text('Bank Transfer'),
                          ),
                        ],
                        onChanged: (v) => setSheetState(() => method = v!),
                      ),
                      const SizedBox(height: 16),
                      SizedBox(
                        width: double.infinity,
                        height: 48,
                        child: FilledButton(
                          onPressed: () async {
                            final amount = double.tryParse(amountCtrl.text);
                            if (amount == null || amount <= 0) return;
                            final ok = await ref
                                .read(posProvider.notifier)
                                .receivePayment({
                                  'amount': amount,
                                  'payment_method': method,
                                  'client_name': nameCtrl.text.trim(),
                                  'client_phone': phoneCtrl.text.trim(),
                                });
                            if (ok && ctx.mounted) Navigator.pop(ctx);
                          },
                          child: const Text('Receive Payment'),
                        ),
                      ),
                    ],
                  ),
                ),
          ),
    );
  }
}

// =====================================================
// TRANSACTION LIST
// =====================================================

class _TransactionList extends ConsumerWidget {
  final List<PosTransaction> transactions;
  final bool showActions;
  const _TransactionList({
    required this.transactions,
    required this.showActions,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final plnFmt = NumberFormat.currency(locale: 'pl', symbol: 'PLN');

    if (transactions.isEmpty) {
      return const Center(child: Text('No transactions'));
    }

    return ListView.builder(
      padding: const EdgeInsets.all(8),
      itemCount: transactions.length,
      itemBuilder: (_, i) {
        final t = transactions[i];
        return Card(
          child: ListTile(
            leading: CircleAvatar(
              backgroundColor:
                  t.isPending ? Colors.orange[100] : Colors.green[100],
              child: Icon(
                t.paymentMethod == 'cash' ? Icons.money : Icons.credit_card,
                color: t.isPending ? Colors.orange : Colors.green,
              ),
            ),
            title: Text(t.clientName ?? t.receiptNumber),
            subtitle: Text('${t.methodLabel} \u2022 ${t.statusLabel}'),
            trailing: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text(
                  plnFmt.format(t.amount),
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                if (showActions && t.status == 'under_review')
                  Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      IconButton(
                        icon: const Icon(
                          Icons.check,
                          color: Colors.green,
                          size: 20,
                        ),
                        onPressed:
                            () => ref.read(posProvider.notifier).approve(t.id),
                        padding: EdgeInsets.zero,
                        constraints: const BoxConstraints(),
                      ),
                      const SizedBox(width: 8),
                      IconButton(
                        icon: const Icon(
                          Icons.close,
                          color: Colors.red,
                          size: 20,
                        ),
                        onPressed: () => _showRejectDialog(context, ref, t.id),
                        padding: EdgeInsets.zero,
                        constraints: const BoxConstraints(),
                      ),
                    ],
                  ),
              ],
            ),
            onTap: () => context.push('/pos/${t.id}'),
          ),
        );
      },
    );
  }

  void _showRejectDialog(BuildContext context, WidgetRef ref, int id) {
    final reasonCtrl = TextEditingController();
    showDialog(
      context: context,
      builder:
          (ctx) => AlertDialog(
            title: const Text('Reject Transaction'),
            content: TextField(
              controller: reasonCtrl,
              decoration: const InputDecoration(
                labelText: 'Reason',
                border: OutlineInputBorder(),
              ),
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(ctx),
                child: const Text('Cancel'),
              ),
              FilledButton(
                onPressed: () {
                  ref.read(posProvider.notifier).reject(id, reasonCtrl.text);
                  Navigator.pop(ctx);
                },
                child: const Text('Reject'),
              ),
            ],
          ),
    );
  }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// PosScreen — POS терминал.
// Tabs: Pending / Completed. FAB → Receive Payment (bottom sheet).
// Receive: amount, client name/phone, method (cash/card/blik/transfer).
// Approve/Reject inline кнопки для under_review транзакций.
// Reject → диалог с причиной.
// Файл: lib/screens/pos/pos_screen.dart
// ---------------------------------------------------------------
