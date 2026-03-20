// =====================================================
// FILE: lib/screens/leads/leads_list_screen.dart
// Leads list with status filter chips
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../providers/leads_provider.dart';
import '../models/models.dart';

class LeadsListScreen extends ConsumerStatefulWidget {
  const LeadsListScreen({super.key});

  @override
  ConsumerState<LeadsListScreen> createState() => _LeadsListScreenState();
}

class _LeadsListScreenState extends ConsumerState<LeadsListScreen> {
  String? _selectedStatus;

  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(leadsProvider.notifier).loadLeads());
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(leadsProvider);

    return Scaffold(
      appBar: AppBar(
        title: Text('Leads (${state.totalCount})'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(leadsProvider.notifier).loadLeads(status: _selectedStatus),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => context.push('/leads/create'),
        child: const Icon(Icons.add),
      ),
      body: Column(
        children: [
          // Status filter chips
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.all(8),
            child: Row(
              children: [
                _FilterChip('All', null),
                _FilterChip('New', 'new'),
                _FilterChip('Contacted', 'contacted'),
                _FilterChip('Consultation', 'consultation'),
                _FilterChip('Contract', 'contract'),
                _FilterChip('Paid', 'paid'),
              ].map((chip) => Padding(
                padding: const EdgeInsets.only(right: 8),
                child: FilterChip(
                  selected: _selectedStatus == chip.value,
                  label: Text(chip.label),
                  onSelected: (_) {
                    setState(() => _selectedStatus = chip.value);
                    ref.read(leadsProvider.notifier).loadLeads(status: chip.value);
                  },
                ),
              )).toList(),
            ),
          ),

          // List
          Expanded(
            child: state.isLoading && state.leads.isEmpty
                ? const Center(child: CircularProgressIndicator())
                : state.leads.isEmpty
                    ? const Center(child: Text('No leads found'))
                    : RefreshIndicator(
                        onRefresh: () => ref.read(leadsProvider.notifier).loadLeads(status: _selectedStatus),
                        child: ListView.builder(
                          itemCount: state.leads.length,
                          itemBuilder: (_, i) => _LeadTile(lead: state.leads[i]),
                        ),
                      ),
          ),
        ],
      ),
    );
  }
}

class _FilterChip {
  final String label;
  final String? value;
  _FilterChip(this.label, this.value);
}

class _LeadTile extends StatelessWidget {
  final Lead lead;
  const _LeadTile({required this.lead});

  Color _statusColor() => switch (lead.status) {
    'new' => Colors.blue,
    'contacted' => Colors.orange,
    'consultation' => Colors.purple,
    'contract' => Colors.teal,
    'paid' => Colors.green,
    'rejected' => Colors.red,
    'spam' => Colors.grey,
    _ => Colors.grey,
  };

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: _statusColor().withValues(alpha: 0.15),
          child: Icon(
            lead.isHighPriority ? Icons.priority_high : Icons.person,
            color: _statusColor(),
          ),
        ),
        title: Text(lead.name, style: const TextStyle(fontWeight: FontWeight.w600)),
        subtitle: Text('${lead.source} \u2022 ${lead.phone}'),
        trailing: Chip(
          label: Text(lead.statusLabel, style: const TextStyle(fontSize: 11, color: Colors.white)),
          backgroundColor: _statusColor(),
          padding: EdgeInsets.zero,
          materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
        ),
        onTap: () => context.push('/leads/${lead.id}'),
      ),
    );
  }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// LeadsListScreen — список лидов с фильтрацией по статусу.
// FilterChip: All, New, Contacted, Consultation, Contract, Paid.
// FAB → /leads/create. Tap → /leads/:id (detail).
// Pull-to-refresh, цветовые метки статусов.
// Файл: lib/screens/leads/leads_list_screen.dart
// ---------------------------------------------------------------
