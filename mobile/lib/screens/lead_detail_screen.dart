// =====================================================
// FILE: lib/screens/leads/lead_detail_screen.dart
// Lead detail with status change + convert to client
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import '../providers/leads_provider.dart';

class LeadDetailScreen extends ConsumerStatefulWidget {
  final int leadId;
  const LeadDetailScreen({super.key, required this.leadId});

  @override
  ConsumerState<LeadDetailScreen> createState() => _LeadDetailScreenState();
}

class _LeadDetailScreenState extends ConsumerState<LeadDetailScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(leadsProvider.notifier).loadLeadDetail(widget.leadId));
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(leadsProvider);
    final lead = state.selectedLead;
    final dateFmt = DateFormat('dd.MM.yyyy HH:mm');

    return Scaffold(
      appBar: AppBar(title: Text(lead?.name ?? 'Lead #${widget.leadId}')),
      body: state.isLoading || lead == null
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              padding: const EdgeInsets.all(16),
              children: [
                // Header card
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                      Row(children: [
                        const Icon(Icons.person, size: 40),
                        const SizedBox(width: 12),
                        Expanded(child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(lead.name, style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                            Text(lead.phone, style: TextStyle(color: Colors.grey[600])),
                            if (lead.email != null) Text(lead.email!, style: TextStyle(color: Colors.grey[600])),
                          ],
                        )),
                      ]),
                    ]),
                  ),
                ),
                const SizedBox(height: 12),

                // Info card
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(children: [
                      _DetailRow('Status', lead.statusLabel),
                      _DetailRow('Source', lead.source),
                      _DetailRow('Priority', lead.priority ?? 'normal'),
                      _DetailRow('Service', lead.serviceType ?? '-'),
                      _DetailRow('Language', lead.language ?? '-'),
                      _DetailRow('Created', dateFmt.format(lead.createdAt)),
                      if (lead.firstContactedAt != null)
                        _DetailRow('First Contact', dateFmt.format(lead.firstContactedAt!)),
                      if (lead.notes != null && lead.notes!.isNotEmpty)
                        _DetailRow('Notes', lead.notes!),
                    ]),
                  ),
                ),
                const SizedBox(height: 24),

                // Status change buttons
                if (!lead.isConverted && lead.status != 'spam' && lead.status != 'rejected') ...[
                  Text('Change Status', style: Theme.of(context).textTheme.titleMedium),
                  const SizedBox(height: 8),
                  Wrap(spacing: 8, runSpacing: 8, children: [
                    if (lead.status == 'new')
                      _ActionButton('Mark Contacted', Colors.orange,
                        () => _changeStatus('contacted')),
                    if (lead.status == 'contacted')
                      _ActionButton('Consultation', Colors.purple,
                        () => _changeStatus('consultation')),
                    if (lead.status == 'consultation')
                      _ActionButton('Contract', Colors.teal,
                        () => _changeStatus('contract')),
                    if (lead.status == 'contract')
                      _ActionButton('Paid', Colors.green,
                        () => _changeStatus('paid')),
                    _ActionButton('Reject', Colors.red, () => _changeStatus('rejected')),
                    _ActionButton('Spam', Colors.grey, () => _changeStatus('spam')),
                  ]),
                  const SizedBox(height: 24),
                ],

                // Convert to client
                if (lead.status == 'paid' && !lead.isConverted)
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: FilledButton.icon(
                      icon: const Icon(Icons.person_add),
                      label: const Text('Convert to Client'),
                      onPressed: () async {
                        final ok = await ref.read(leadsProvider.notifier).convertToClient(lead.id);
                        if (ok && mounted) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text('Lead converted to client')),
                          );
                          Navigator.of(context).pop();
                        }
                      },
                    ),
                  ),

                if (lead.isConverted)
                  Card(
                    color: Colors.green[50],
                    child: const Padding(
                      padding: EdgeInsets.all(16),
                      child: Row(children: [
                        Icon(Icons.check_circle, color: Colors.green),
                        SizedBox(width: 8),
                        Text('Converted to client', style: TextStyle(fontWeight: FontWeight.w600)),
                      ]),
                    ),
                  ),
              ],
            ),
    );
  }

  Future<void> _changeStatus(String status) async {
    final ok = await ref.read(leadsProvider.notifier).updateStatus(widget.leadId, status);
    if (ok) {
      ref.read(leadsProvider.notifier).loadLeadDetail(widget.leadId);
    }
  }
}

class _DetailRow extends StatelessWidget {
  final String label;
  final String value;
  const _DetailRow(this.label, this.value);

  @override
  Widget build(BuildContext context) => Padding(
    padding: const EdgeInsets.symmetric(vertical: 6),
    child: Row(crossAxisAlignment: CrossAxisAlignment.start, children: [
      SizedBox(width: 110, child: Text(label, style: TextStyle(color: Colors.grey[600]))),
      Expanded(child: Text(value, style: const TextStyle(fontWeight: FontWeight.w500))),
    ]),
  );
}

class _ActionButton extends StatelessWidget {
  final String label;
  final Color color;
  final VoidCallback onTap;
  const _ActionButton(this.label, this.color, this.onTap);

  @override
  Widget build(BuildContext context) => ElevatedButton(
    onPressed: onTap,
    style: ElevatedButton.styleFrom(backgroundColor: color, foregroundColor: Colors.white),
    child: Text(label),
  );
}

// ---------------------------------------------------------------
// Аннотация (RU):
// LeadDetailScreen — детали лида.
// Header: имя, телефон, email. Info: status, source, priority, service, language.
// Кнопки смены статуса по порядку воронки: new→contacted→consultation→contract→paid.
// Convert to Client — кнопка при статусе 'paid' и !isConverted.
// Файл: lib/screens/leads/lead_detail_screen.dart
// ---------------------------------------------------------------
