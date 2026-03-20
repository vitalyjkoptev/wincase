// =====================================================
// WINCASE CRM -- Staff Cases Screen
// Filter by status, case cards with progress, tap for detail
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../providers/staff_provider.dart';
import '../../models/staff_models.dart';
import '../../providers/auth_provider.dart';

class StaffCasesScreen extends ConsumerStatefulWidget {
  const StaffCasesScreen({super.key});

  @override
  ConsumerState<StaffCasesScreen> createState() => _StaffCasesScreenState();
}

class _StaffCasesScreenState extends ConsumerState<StaffCasesScreen> {
  String _selectedStatus = 'all';

  static const _statuses = [
    'all', 'active', 'pending_docs', 'submitted', 'in_review', 'approved', 'completed', 'on_hold',
  ];

  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(staffCasesProvider.notifier).load());
  }

  void _onStatusChanged(String status) {
    setState(() => _selectedStatus = status);
    ref.read(staffCasesProvider.notifier).load(
      status: status == 'all' ? null : status,
    );
  }

  void _openCaseDetail(StaffCase caseData) {
    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => _CaseDetailPage(caseId: caseData.id, initialCase: caseData),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(staffCasesProvider);
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('My Cases'),
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(52),
          child: SizedBox(
            height: 52,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              children: _statuses.map((status) {
                final isSelected = _selectedStatus == status;
                return Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: FilterChip(
                    label: Text(_statusLabel(status)),
                    selected: isSelected,
                    onSelected: (_) => _onStatusChanged(status),
                  ),
                );
              }).toList(),
            ),
          ),
        ),
      ),
      body: _buildBody(state, theme),
    );
  }

  Widget _buildBody(CasesState state, ThemeData theme) {
    if (state.isLoading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (state.error != null) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.error_outline, size: 48, color: theme.colorScheme.error),
            const SizedBox(height: 8),
            Text('Failed to load cases'),
            const SizedBox(height: 8),
            FilledButton.tonal(
              onPressed: () => ref.read(staffCasesProvider.notifier).load(),
              child: const Text('Retry'),
            ),
          ],
        ),
      );
    }

    if (state.cases.isEmpty) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.folder_off_outlined, size: 64, color: theme.colorScheme.outline),
            const SizedBox(height: 16),
            Text('No cases found', style: theme.textTheme.titleMedium),
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: () => ref.read(staffCasesProvider.notifier).load(
        status: _selectedStatus == 'all' ? null : _selectedStatus,
      ),
      child: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: state.cases.length,
        itemBuilder: (context, index) => _CaseCard(
          caseData: state.cases[index],
          onTap: () => _openCaseDetail(state.cases[index]),
        ),
      ),
    );
  }

  String _statusLabel(String status) => switch (status) {
    'all'          => 'All',
    'active'       => 'Active',
    'pending_docs' => 'Pending Docs',
    'submitted'    => 'Submitted',
    'in_review'    => 'In Review',
    'approved'     => 'Approved',
    'completed'    => 'Completed',
    'on_hold'      => 'On Hold',
    _              => status,
  };
}

// =====================================================
// CASE CARD
// =====================================================

class _CaseCard extends StatelessWidget {
  final StaffCase caseData;
  final VoidCallback onTap;

  const _CaseCard({required this.caseData, required this.onTap});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;
    final progress = (caseData.progressPercentage ?? 0) / 100.0;
    final priorityColor = _priorityColor(caseData.priority);

    return Card(
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 10),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: BorderSide(
          color: caseData.isUrgent ? priorityColor.withOpacity(0.5) : colorScheme.outlineVariant,
          width: caseData.isUrgent ? 1.5 : 0.5,
        ),
      ),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header: case number + priority
              Row(
                children: [
                  Container(
                    width: 4,
                    height: 32,
                    decoration: BoxDecoration(
                      color: priorityColor,
                      borderRadius: BorderRadius.circular(2),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          caseData.caseNumber,
                          style: theme.textTheme.titleSmall?.copyWith(fontWeight: FontWeight.bold),
                        ),
                        if (caseData.client != null)
                          Text(
                            caseData.client!.displayName,
                            style: theme.textTheme.bodySmall?.copyWith(color: colorScheme.outline),
                          ),
                      ],
                    ),
                  ),
                  _PriorityBadge(priority: caseData.priority),
                ],
              ),
              const SizedBox(height: 12),

              // Service type
              Row(
                children: [
                  Icon(Icons.description_outlined, size: 16, color: colorScheme.outline),
                  const SizedBox(width: 4),
                  Text(caseData.serviceLabel, style: theme.textTheme.bodyMedium),
                ],
              ),
              const SizedBox(height: 4),

              // Deadline
              if (caseData.deadline != null)
                Row(
                  children: [
                    Icon(
                      Icons.event,
                      size: 16,
                      color: caseData.isOverdue ? colorScheme.error : colorScheme.outline,
                    ),
                    const SizedBox(width: 4),
                    Text(
                      _formatDate(caseData.deadline!),
                      style: theme.textTheme.bodySmall?.copyWith(
                        color: caseData.isOverdue ? colorScheme.error : colorScheme.outline,
                        fontWeight: caseData.isOverdue ? FontWeight.bold : null,
                      ),
                    ),
                    if (caseData.isOverdue) ...[
                      const SizedBox(width: 4),
                      Text(
                        'OVERDUE',
                        style: theme.textTheme.labelSmall?.copyWith(
                          color: colorScheme.error,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ],
                  ],
                ),
              const SizedBox(height: 12),

              // Progress bar
              Row(
                children: [
                  Expanded(
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(4),
                      child: LinearProgressIndicator(
                        value: progress,
                        minHeight: 6,
                        backgroundColor: colorScheme.surfaceContainerHighest,
                        color: _progressColor(progress),
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Text(
                    '${caseData.progressPercentage ?? 0}%',
                    style: theme.textTheme.labelSmall?.copyWith(fontWeight: FontWeight.bold),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Color _priorityColor(String? priority) => switch (priority) {
    'urgent' => Colors.red,
    'high'   => Colors.orange,
    'medium' => Colors.blue,
    'low'    => Colors.grey,
    _        => Colors.blue,
  };

  Color _progressColor(double value) {
    if (value >= 0.8) return Colors.green;
    if (value >= 0.5) return Colors.blue;
    if (value >= 0.25) return Colors.orange;
    return Colors.grey;
  }

  String _formatDate(DateTime date) {
    return '${date.day.toString().padLeft(2, '0')}.${date.month.toString().padLeft(2, '0')}.${date.year}';
  }
}

// =====================================================
// PRIORITY BADGE
// =====================================================

class _PriorityBadge extends StatelessWidget {
  final String? priority;

  const _PriorityBadge({this.priority});

  @override
  Widget build(BuildContext context) {
    if (priority == null) return const SizedBox.shrink();

    final (Color bg, Color fg) = switch (priority) {
      'urgent' => (Colors.red.shade50, Colors.red.shade800),
      'high'   => (Colors.orange.shade50, Colors.orange.shade800),
      'medium' => (Colors.blue.shade50, Colors.blue.shade800),
      'low'    => (Colors.grey.shade100, Colors.grey.shade700),
      _        => (Colors.grey.shade100, Colors.grey.shade700),
    };

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(4),
      ),
      child: Text(
        priority![0].toUpperCase() + priority!.substring(1),
        style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: fg),
      ),
    );
  }
}

// =====================================================
// CASE DETAIL PAGE (with tabs)
// =====================================================

class _CaseDetailPage extends ConsumerStatefulWidget {
  final int caseId;
  final StaffCase initialCase;

  const _CaseDetailPage({required this.caseId, required this.initialCase});

  @override
  ConsumerState<_CaseDetailPage> createState() => _CaseDetailPageState();
}

class _CaseDetailPageState extends ConsumerState<_CaseDetailPage> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  StaffCaseDetail? _detail;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    Future.microtask(_loadDetail);
  }

  Future<void> _loadDetail() async {
    try {
      final detail = await ref.read(staffCasesProvider.notifier).getDetail(widget.caseId);
      if (mounted) {
        setState(() {
          _detail = detail;
          _isLoading = false;
        });
      }
    } catch (_) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final caseData = _detail?.caseData ?? widget.initialCase;

    return Scaffold(
      appBar: AppBar(
        title: Text(caseData.caseNumber),
        bottom: TabBar(
          controller: _tabController,
          tabs: const [
            Tab(text: 'Overview'),
            Tab(text: 'Documents'),
            Tab(text: 'Tasks'),
            Tab(text: 'Timeline'),
          ],
        ),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : TabBarView(
              controller: _tabController,
              children: [
                _OverviewTab(caseData: caseData, progress: _detail?.progress),
                _DocumentsTab(caseId: widget.caseId),
                _TasksTab(tasks: caseData.tasks ?? []),
                _TimelineTab(caseId: widget.caseId),
              ],
            ),
    );
  }
}

// --- Overview Tab ---
class _OverviewTab extends StatelessWidget {
  final StaffCase caseData;
  final CaseProgress? progress;

  const _OverviewTab({required this.caseData, this.progress});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Client info
          Card(
            elevation: 0,
            child: ListTile(
              leading: const Icon(Icons.person),
              title: Text(caseData.client?.displayName ?? 'Unknown Client'),
              subtitle: Text(caseData.client?.phone ?? ''),
            ),
          ),
          const SizedBox(height: 12),

          // Case info
          Card(
            elevation: 0,
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Case Information', style: theme.textTheme.titleSmall?.copyWith(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  _InfoRow('Service', caseData.serviceLabel),
                  _InfoRow('Status', caseData.statusLabel),
                  _InfoRow('Priority', caseData.priority ?? 'Normal'),
                  if (caseData.deadline != null)
                    _InfoRow('Deadline', '${caseData.deadline!.day}.${caseData.deadline!.month}.${caseData.deadline!.year}'),
                ],
              ),
            ),
          ),
          const SizedBox(height: 12),

          // Progress
          if (progress != null) ...[
            Card(
              elevation: 0,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Text('Progress', style: theme.textTheme.titleSmall?.copyWith(fontWeight: FontWeight.bold)),
                        const Spacer(),
                        Text('${progress!.pct}%', style: theme.textTheme.titleSmall?.copyWith(fontWeight: FontWeight.bold)),
                      ],
                    ),
                    const SizedBox(height: 8),
                    ClipRRect(
                      borderRadius: BorderRadius.circular(4),
                      child: LinearProgressIndicator(
                        value: progress!.pct / 100.0,
                        minHeight: 8,
                        backgroundColor: colorScheme.surfaceContainerHighest,
                      ),
                    ),
                    const SizedBox(height: 12),
                    ...progress!.stages.asMap().entries.map((entry) {
                      final idx = entry.key;
                      final stage = entry.value;
                      final isCurrent = idx == progress!.stageIndex;
                      final isDone = idx < progress!.stageIndex;
                      return Padding(
                        padding: const EdgeInsets.symmetric(vertical: 2),
                        child: Row(
                          children: [
                            Icon(
                              isDone ? Icons.check_circle : (isCurrent ? Icons.radio_button_checked : Icons.radio_button_unchecked),
                              size: 18,
                              color: isDone ? Colors.green : (isCurrent ? colorScheme.primary : colorScheme.outline),
                            ),
                            const SizedBox(width: 8),
                            Text(
                              stage,
                              style: theme.textTheme.bodySmall?.copyWith(
                                fontWeight: isCurrent ? FontWeight.bold : null,
                                color: isDone ? Colors.green.shade700 : null,
                              ),
                            ),
                          ],
                        ),
                      );
                    }),
                  ],
                ),
              ),
            ),
          ],
        ],
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
    final theme = Theme.of(context);
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          SizedBox(
            width: 100,
            child: Text(label, style: theme.textTheme.bodySmall?.copyWith(color: theme.colorScheme.outline)),
          ),
          Expanded(child: Text(value, style: theme.textTheme.bodyMedium)),
        ],
      ),
    );
  }
}

// --- Documents Tab ---
class _DocumentsTab extends StatelessWidget {
  final int caseId;

  const _DocumentsTab({required this.caseId});

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.description_outlined, size: 48, color: Colors.grey),
          SizedBox(height: 8),
          Text('Case documents will appear here'),
        ],
      ),
    );
  }
}

// --- Tasks Tab ---
class _TasksTab extends StatelessWidget {
  final List<StaffTask> tasks;

  const _TasksTab({required this.tasks});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    if (tasks.isEmpty) {
      return const Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.task_outlined, size: 48, color: Colors.grey),
            SizedBox(height: 8),
            Text('No tasks for this case'),
          ],
        ),
      );
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: tasks.length,
      itemBuilder: (context, index) {
        final task = tasks[index];
        return Card(
          elevation: 0,
          margin: const EdgeInsets.only(bottom: 8),
          child: ListTile(
            leading: Icon(
              task.isCompleted ? Icons.check_circle : Icons.circle_outlined,
              color: task.isCompleted ? Colors.green : (task.isOverdue ? Colors.red : theme.colorScheme.outline),
            ),
            title: Text(
              task.title,
              style: theme.textTheme.bodyMedium?.copyWith(
                decoration: task.isCompleted ? TextDecoration.lineThrough : null,
              ),
            ),
            subtitle: task.dueDate != null
                ? Text(
                    'Due: ${task.dueDate!.day}.${task.dueDate!.month}.${task.dueDate!.year}',
                    style: theme.textTheme.bodySmall?.copyWith(
                      color: task.isOverdue ? Colors.red : null,
                    ),
                  )
                : null,
            trailing: _PriorityBadge(priority: task.priority),
            dense: true,
          ),
        );
      },
    );
  }
}

// --- Timeline Tab ---
class _TimelineTab extends StatelessWidget {
  final int caseId;

  const _TimelineTab({required this.caseId});

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.timeline, size: 48, color: Colors.grey),
          SizedBox(height: 8),
          Text('Case timeline will appear here'),
        ],
      ),
    );
  }
}
