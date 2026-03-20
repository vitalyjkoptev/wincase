// =====================================================
// WINCASE CRM -- Staff Tasks Screen
// Overdue / Today / Upcoming sections, swipe to complete
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../providers/staff_provider.dart';
import '../../models/staff_models.dart';
import '../../providers/auth_provider.dart';

class StaffTasksScreen extends ConsumerStatefulWidget {
  const StaffTasksScreen({super.key});

  @override
  ConsumerState<StaffTasksScreen> createState() => _StaffTasksScreenState();
}

class _StaffTasksScreenState extends ConsumerState<StaffTasksScreen> {
  String? _priorityFilter;

  static const _priorities = ['all', 'urgent', 'high', 'medium', 'low'];

  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(staffTasksProvider.notifier).load());
  }

  void _onPriorityChanged(String priority) {
    setState(() => _priorityFilter = priority == 'all' ? null : priority);
    ref.read(staffTasksProvider.notifier).load(
      priority: priority == 'all' ? null : priority,
    );
  }

  Future<void> _onRefresh() async {
    await ref.read(staffTasksProvider.notifier).load(priority: _priorityFilter);
  }

  void _completeTask(int taskId) {
    ref.read(staffTasksProvider.notifier).completeTask(taskId);
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(staffTasksProvider);
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('My Tasks'),
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(52),
          child: SizedBox(
            height: 52,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              children: _priorities.map((p) {
                final isSelected = (_priorityFilter ?? 'all') == p;
                return Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: FilterChip(
                    label: Text(p[0].toUpperCase() + p.substring(1)),
                    selected: isSelected,
                    onSelected: (_) => _onPriorityChanged(p),
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

  Widget _buildBody(TasksState state, ThemeData theme) {
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
            Text('Failed to load tasks'),
            const SizedBox(height: 8),
            FilledButton.tonal(onPressed: _onRefresh, child: const Text('Retry')),
          ],
        ),
      );
    }

    final overdue = state.overdue;
    final today = state.dueToday;
    final upcoming = state.upcoming;

    if (overdue.isEmpty && today.isEmpty && upcoming.isEmpty) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.task_alt, size: 64, color: theme.colorScheme.outline),
            const SizedBox(height: 16),
            Text('All caught up!', style: theme.textTheme.titleMedium),
            const SizedBox(height: 4),
            Text('No tasks to show', style: theme.textTheme.bodyMedium?.copyWith(color: theme.colorScheme.outline)),
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: _onRefresh,
      child: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // --- OVERDUE ---
          if (overdue.isNotEmpty) ...[
            _SectionBanner(
              title: 'OVERDUE',
              count: overdue.length,
              color: theme.colorScheme.error,
              icon: Icons.warning_amber_rounded,
            ),
            const SizedBox(height: 8),
            ...overdue.map((task) => _TaskCard(
              task: task,
              accentColor: theme.colorScheme.error,
              onComplete: () => _completeTask(task.id),
            )),
            const SizedBox(height: 20),
          ],

          // --- TODAY ---
          if (today.isNotEmpty) ...[
            _SectionBanner(
              title: 'TODAY',
              count: today.length,
              color: Colors.orange.shade700,
              icon: Icons.today,
            ),
            const SizedBox(height: 8),
            ...today.map((task) => _TaskCard(
              task: task,
              accentColor: Colors.orange.shade700,
              onComplete: () => _completeTask(task.id),
            )),
            const SizedBox(height: 20),
          ],

          // --- UPCOMING ---
          if (upcoming.isNotEmpty) ...[
            _SectionBanner(
              title: 'UPCOMING',
              count: upcoming.length,
              color: theme.colorScheme.primary,
              icon: Icons.upcoming,
            ),
            const SizedBox(height: 8),
            ...upcoming.map((task) => _TaskCard(
              task: task,
              accentColor: theme.colorScheme.primary,
              onComplete: () => _completeTask(task.id),
            )),
          ],
        ],
      ),
    );
  }
}

// =====================================================
// SECTION BANNER
// =====================================================

class _SectionBanner extends StatelessWidget {
  final String title;
  final int count;
  final Color color;
  final IconData icon;

  const _SectionBanner({
    required this.title,
    required this.count,
    required this.color,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border(left: BorderSide(color: color, width: 3)),
      ),
      child: Row(
        children: [
          Icon(icon, size: 18, color: color),
          const SizedBox(width: 8),
          Text(
            title,
            style: theme.textTheme.titleSmall?.copyWith(
              color: color,
              fontWeight: FontWeight.bold,
              letterSpacing: 1,
            ),
          ),
          const Spacer(),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
            decoration: BoxDecoration(
              color: color.withOpacity(0.2),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Text(
              '$count',
              style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: color),
            ),
          ),
        ],
      ),
    );
  }
}

// =====================================================
// TASK CARD (swipe to complete)
// =====================================================

class _TaskCard extends StatelessWidget {
  final StaffTask task;
  final Color accentColor;
  final VoidCallback onComplete;

  const _TaskCard({
    required this.task,
    required this.accentColor,
    required this.onComplete,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return Dismissible(
      key: ValueKey('task_${task.id}'),
      direction: task.isCompleted ? DismissDirection.none : DismissDirection.startToEnd,
      onDismissed: (_) => onComplete(),
      background: Container(
        alignment: Alignment.centerLeft,
        padding: const EdgeInsets.only(left: 20),
        margin: const EdgeInsets.only(bottom: 8),
        decoration: BoxDecoration(
          color: Colors.green,
          borderRadius: BorderRadius.circular(12),
        ),
        child: const Row(
          children: [
            Icon(Icons.check, color: Colors.white),
            SizedBox(width: 8),
            Text('Complete', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
          ],
        ),
      ),
      child: Card(
        elevation: 0,
        margin: const EdgeInsets.only(bottom: 8),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(12),
          side: BorderSide(color: colorScheme.outlineVariant, width: 0.5),
        ),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              // Checkbox
              SizedBox(
                width: 28,
                height: 28,
                child: Checkbox(
                  value: task.isCompleted,
                  onChanged: task.isCompleted ? null : (_) => onComplete(),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
                ),
              ),
              const SizedBox(width: 12),

              // Content
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      task.title,
                      style: theme.textTheme.bodyMedium?.copyWith(
                        fontWeight: FontWeight.w600,
                        decoration: task.isCompleted ? TextDecoration.lineThrough : null,
                        color: task.isCompleted ? colorScheme.outline : null,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        if (task.clientName != null) ...[
                          Icon(Icons.person, size: 12, color: colorScheme.outline),
                          const SizedBox(width: 2),
                          Text(
                            task.clientName!,
                            style: theme.textTheme.labelSmall?.copyWith(color: colorScheme.outline),
                          ),
                          const SizedBox(width: 8),
                        ],
                        if (task.caseNumber != null) ...[
                          Icon(Icons.folder, size: 12, color: colorScheme.outline),
                          const SizedBox(width: 2),
                          Text(
                            task.caseNumber!,
                            style: theme.textTheme.labelSmall?.copyWith(color: colorScheme.outline),
                          ),
                        ],
                      ],
                    ),
                    if (task.dueDate != null) ...[
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          Icon(Icons.schedule, size: 12, color: accentColor),
                          const SizedBox(width: 2),
                          Text(
                            _formatDate(task.dueDate!),
                            style: theme.textTheme.labelSmall?.copyWith(color: accentColor),
                          ),
                        ],
                      ),
                    ],
                  ],
                ),
              ),

              // Priority badge
              _PriorityBadge(priority: task.priority),
            ],
          ),
        ),
      ),
    );
  }

  String _formatDate(DateTime date) {
    return '${date.day.toString().padLeft(2, '0')}.${date.month.toString().padLeft(2, '0')}.${date.year}';
  }
}

// =====================================================
// PRIORITY BADGE
// =====================================================

class _PriorityBadge extends StatelessWidget {
  final String priority;

  const _PriorityBadge({required this.priority});

  @override
  Widget build(BuildContext context) {
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
        priority[0].toUpperCase() + priority.substring(1),
        style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: fg),
      ),
    );
  }
}
