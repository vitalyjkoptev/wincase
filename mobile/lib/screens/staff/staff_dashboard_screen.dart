// =====================================================
// WINCASE CRM -- Staff Dashboard Screen
// Welcome card, stats, tasks, inbox, deadlines, quick actions
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../providers/staff_provider.dart';
import '../../models/staff_models.dart';
import '../../providers/auth_provider.dart';

class StaffDashboardScreen extends ConsumerStatefulWidget {
  const StaffDashboardScreen({super.key});

  @override
  ConsumerState<StaffDashboardScreen> createState() => _StaffDashboardScreenState();
}

class _StaffDashboardScreenState extends ConsumerState<StaffDashboardScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(staffDashboardProvider.notifier).load());
  }

  Future<void> _onRefresh() async {
    await ref.read(staffDashboardProvider.notifier).load();
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(staffDashboardProvider);
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    if (state.isLoading && state.dashboard == null) {
      return const Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }

    if (state.error != null && state.dashboard == null) {
      return Scaffold(
        body: Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(Icons.error_outline, size: 48, color: colorScheme.error),
              const SizedBox(height: 16),
              Text('Failed to load dashboard', style: theme.textTheme.titleMedium),
              const SizedBox(height: 8),
              FilledButton.tonal(
                onPressed: _onRefresh,
                child: const Text('Retry'),
              ),
            ],
          ),
        ),
      );
    }

    final dashboard = state.dashboard;
    if (dashboard == null) {
      return const Scaffold(body: Center(child: Text('No data')));
    }

    return Scaffold(
      body: RefreshIndicator(
        onRefresh: _onRefresh,
        child: CustomScrollView(
          slivers: [
            // --- App Bar ---
            SliverAppBar.medium(
              title: const Text('Dashboard'),
              actions: [
                IconButton(
                  icon: const Icon(Icons.notifications_outlined),
                  onPressed: () {},
                ),
              ],
            ),

            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              sliver: SliverList.list(
                children: [
                  // --- Welcome Card ---
                  _WelcomeCard(
                    user: dashboard.user,
                    isClockedIn: dashboard.isClockedIn,
                    clockInTime: dashboard.clockInTime,
                    onClockToggle: () {
                      if (dashboard.isClockedIn) {
                        ref.read(staffDashboardProvider.notifier).clockOut();
                      } else {
                        ref.read(staffDashboardProvider.notifier).clockIn();
                      }
                    },
                  ),
                  const SizedBox(height: 16),

                  // --- Stats Row ---
                  _StatsGrid(stats: dashboard.stats),
                  const SizedBox(height: 24),

                  // --- Today's Tasks ---
                  _SectionHeader(
                    title: "Today's Tasks",
                    count: dashboard.todayTasks.length,
                    icon: Icons.task_alt,
                  ),
                  const SizedBox(height: 8),
                  if (dashboard.todayTasks.isEmpty)
                    _EmptyPlaceholder(
                      icon: Icons.check_circle_outline,
                      message: 'No tasks for today',
                    )
                  else
                    ...dashboard.todayTasks.map((task) => _TaskCheckItem(
                      task: task,
                      onComplete: () {
                        ref.read(staffTasksProvider.notifier).completeTask(task.id);
                        ref.read(staffDashboardProvider.notifier).load();
                      },
                    )),
                  const SizedBox(height: 24),

                  // --- Unified Inbox ---
                  _SectionHeader(
                    title: 'Inbox',
                    count: dashboard.inbox.length,
                    icon: Icons.inbox,
                  ),
                  const SizedBox(height: 8),
                  if (dashboard.inbox.isEmpty)
                    _EmptyPlaceholder(
                      icon: Icons.inbox_outlined,
                      message: 'No new messages',
                    )
                  else
                    ...dashboard.inbox.take(5).map((msg) => _InboxMessageTile(message: msg)),
                  const SizedBox(height: 24),

                  // --- Upcoming Deadlines ---
                  _SectionHeader(
                    title: 'Upcoming Deadlines',
                    count: dashboard.deadlines.length,
                    icon: Icons.event_busy,
                  ),
                  const SizedBox(height: 8),
                  if (dashboard.deadlines.isEmpty)
                    _EmptyPlaceholder(
                      icon: Icons.event_available,
                      message: 'No upcoming deadlines',
                    )
                  else
                    ...dashboard.deadlines.take(5).map((c) => _DeadlineTile(caseData: c)),
                  const SizedBox(height: 24),

                  // --- Quick Actions ---
                  Text('Quick Actions', style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  _QuickActionsRow(),
                  const SizedBox(height: 32),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// =====================================================
// WELCOME CARD
// =====================================================

class _WelcomeCard extends StatelessWidget {
  final StaffUser user;
  final bool isClockedIn;
  final String? clockInTime;
  final VoidCallback onClockToggle;

  const _WelcomeCard({
    required this.user,
    required this.isClockedIn,
    this.clockInTime,
    required this.onClockToggle,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return Card(
      elevation: 0,
      color: colorScheme.primaryContainer,
      child: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                CircleAvatar(
                  radius: 24,
                  backgroundImage: user.avatarUrl != null ? NetworkImage(user.avatarUrl!) : null,
                  child: user.avatarUrl == null
                      ? Text(
                          user.name.isNotEmpty ? user.name[0].toUpperCase() : '?',
                          style: theme.textTheme.titleLarge,
                        )
                      : null,
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Welcome, ${user.name}',
                        style: theme.textTheme.titleMedium?.copyWith(
                          color: colorScheme.onPrimaryContainer,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      if (user.role != null)
                        Text(
                          user.role!,
                          style: theme.textTheme.bodySmall?.copyWith(
                            color: colorScheme.onPrimaryContainer.withOpacity(0.7),
                          ),
                        ),
                    ],
                  ),
                ),
              ],
            ),
            if (user.todaySchedule != null) ...[
              const SizedBox(height: 12),
              Row(
                children: [
                  Icon(Icons.schedule, size: 16, color: colorScheme.onPrimaryContainer.withOpacity(0.7)),
                  const SizedBox(width: 4),
                  Text(
                    user.todaySchedule!,
                    style: theme.textTheme.bodySmall?.copyWith(
                      color: colorScheme.onPrimaryContainer.withOpacity(0.7),
                    ),
                  ),
                ],
              ),
            ],
            const SizedBox(height: 16),
            Row(
              children: [
                FilledButton.icon(
                  onPressed: onClockToggle,
                  icon: Icon(isClockedIn ? Icons.logout : Icons.login),
                  label: Text(isClockedIn ? 'Clock Out' : 'Clock In'),
                  style: FilledButton.styleFrom(
                    backgroundColor: isClockedIn
                        ? colorScheme.error
                        : colorScheme.primary,
                    foregroundColor: isClockedIn
                        ? colorScheme.onError
                        : colorScheme.onPrimary,
                  ),
                ),
                if (isClockedIn && clockInTime != null) ...[
                  const SizedBox(width: 12),
                  Text(
                    'Since $clockInTime',
                    style: theme.textTheme.bodySmall?.copyWith(
                      color: colorScheme.onPrimaryContainer.withOpacity(0.7),
                    ),
                  ),
                ],
              ],
            ),
          ],
        ),
      ),
    );
  }
}

// =====================================================
// STATS GRID
// =====================================================

class _StatsGrid extends StatelessWidget {
  final StaffDashboardStats stats;

  const _StatsGrid({required this.stats});

  @override
  Widget build(BuildContext context) {
    final items = [
      _StatItem('Clients', stats.myClients, Icons.people, Colors.blue),
      _StatItem('Cases', stats.activeCases, Icons.folder, Colors.indigo),
      _StatItem('Tasks Today', stats.tasksToday, Icons.task_alt, Colors.teal),
      _StatItem('Overdue', stats.tasksOverdue, Icons.warning, Colors.orange),
      _StatItem('Unread', stats.unreadTotal, Icons.mark_email_unread, Colors.purple),
      _StatItem('Expiring', stats.expiringDocs, Icons.description, Colors.red),
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
      itemBuilder: (context, index) {
        final item = items[index];
        return Card(
          elevation: 0,
          color: item.color.withOpacity(0.1),
          child: Padding(
            padding: const EdgeInsets.all(12),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(item.icon, color: item.color, size: 24),
                const SizedBox(height: 4),
                Text(
                  '${item.value}',
                  style: Theme.of(context).textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                    color: item.color,
                  ),
                ),
                Text(
                  item.label,
                  style: Theme.of(context).textTheme.labelSmall?.copyWith(
                    color: item.color.withOpacity(0.8),
                  ),
                  textAlign: TextAlign.center,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
        );
      },
    );
  }
}

class _StatItem {
  final String label;
  final int value;
  final IconData icon;
  final Color color;

  const _StatItem(this.label, this.value, this.icon, this.color);
}

// =====================================================
// SECTION HEADER
// =====================================================

class _SectionHeader extends StatelessWidget {
  final String title;
  final int count;
  final IconData icon;

  const _SectionHeader({
    required this.title,
    required this.count,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Row(
      children: [
        Icon(icon, size: 20, color: theme.colorScheme.primary),
        const SizedBox(width: 8),
        Text(title, style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold)),
        const Spacer(),
        if (count > 0)
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
            decoration: BoxDecoration(
              color: theme.colorScheme.primaryContainer,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Text(
              '$count',
              style: theme.textTheme.labelSmall?.copyWith(color: theme.colorScheme.onPrimaryContainer),
            ),
          ),
      ],
    );
  }
}

// =====================================================
// EMPTY PLACEHOLDER
// =====================================================

class _EmptyPlaceholder extends StatelessWidget {
  final IconData icon;
  final String message;

  const _EmptyPlaceholder({required this.icon, required this.message});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 16),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(icon, size: 20, color: theme.colorScheme.outline),
          const SizedBox(width: 8),
          Text(message, style: theme.textTheme.bodyMedium?.copyWith(color: theme.colorScheme.outline)),
        ],
      ),
    );
  }
}

// =====================================================
// TASK CHECK ITEM
// =====================================================

class _TaskCheckItem extends StatelessWidget {
  final StaffTask task;
  final VoidCallback onComplete;

  const _TaskCheckItem({required this.task, required this.onComplete});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isOverdue = task.isOverdue;

    return Card(
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 4),
      color: isOverdue ? theme.colorScheme.errorContainer.withOpacity(0.3) : null,
      child: ListTile(
        leading: Checkbox(
          value: task.isCompleted,
          onChanged: task.isCompleted ? null : (_) => onComplete(),
        ),
        title: Text(
          task.title,
          style: theme.textTheme.bodyMedium?.copyWith(
            decoration: task.isCompleted ? TextDecoration.lineThrough : null,
          ),
        ),
        subtitle: task.clientName != null
            ? Text(task.clientName!, style: theme.textTheme.bodySmall)
            : null,
        trailing: isOverdue
            ? Icon(Icons.warning_amber, color: theme.colorScheme.error, size: 20)
            : null,
        dense: true,
      ),
    );
  }
}

// =====================================================
// INBOX MESSAGE TILE
// =====================================================

class _InboxMessageTile extends StatelessWidget {
  final InboxMessage message;

  const _InboxMessageTile({required this.message});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isBoss = message.isFromBoss;

    return Card(
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 4),
      child: ListTile(
        leading: CircleAvatar(
          radius: 18,
          backgroundColor: isBoss
              ? theme.colorScheme.tertiary
              : theme.colorScheme.secondaryContainer,
          backgroundImage: message.avatarUrl != null ? NetworkImage(message.avatarUrl!) : null,
          child: message.avatarUrl == null
              ? Icon(
                  isBoss ? Icons.admin_panel_settings : Icons.person,
                  size: 18,
                  color: isBoss ? theme.colorScheme.onTertiary : theme.colorScheme.onSecondaryContainer,
                )
              : null,
        ),
        title: Row(
          children: [
            Expanded(
              child: Text(
                message.fromName,
                style: theme.textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.w600),
                maxLines: 1,
                overflow: TextOverflow.ellipsis,
              ),
            ),
            if (isBoss)
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 1),
                decoration: BoxDecoration(
                  color: theme.colorScheme.tertiary,
                  borderRadius: BorderRadius.circular(4),
                ),
                child: Text(
                  'Boss',
                  style: theme.textTheme.labelSmall?.copyWith(
                    color: theme.colorScheme.onTertiary,
                    fontSize: 9,
                  ),
                ),
              ),
          ],
        ),
        subtitle: Text(
          message.preview,
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
          style: theme.textTheme.bodySmall,
        ),
        dense: true,
      ),
    );
  }
}

// =====================================================
// DEADLINE TILE
// =====================================================

class _DeadlineTile extends StatelessWidget {
  final StaffCase caseData;

  const _DeadlineTile({required this.caseData});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isOverdue = caseData.isOverdue;
    final daysLeft = caseData.deadline != null
        ? caseData.deadline!.difference(DateTime.now()).inDays
        : null;

    return Card(
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 4),
      color: isOverdue ? theme.colorScheme.errorContainer.withOpacity(0.3) : null,
      child: ListTile(
        leading: Icon(
          isOverdue ? Icons.error : Icons.event,
          color: isOverdue ? theme.colorScheme.error : theme.colorScheme.primary,
        ),
        title: Text(
          '${caseData.caseNumber} - ${caseData.serviceLabel}',
          style: theme.textTheme.bodyMedium,
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
        subtitle: Text(
          caseData.client?.displayName ?? '',
          style: theme.textTheme.bodySmall,
        ),
        trailing: daysLeft != null
            ? Text(
                isOverdue ? '${daysLeft.abs()}d late' : '${daysLeft}d left',
                style: theme.textTheme.labelSmall?.copyWith(
                  color: isOverdue ? theme.colorScheme.error : theme.colorScheme.primary,
                  fontWeight: FontWeight.bold,
                ),
              )
            : null,
        dense: true,
      ),
    );
  }
}

// =====================================================
// QUICK ACTIONS ROW
// =====================================================

class _QuickActionsRow extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    final actions = [
      _QuickAction('My Clients', Icons.people, Colors.blue, '/clients'),
      _QuickAction('My Cases', Icons.work, Colors.indigo, '/cases'),
      _QuickAction('Calendar', Icons.calendar_month, Colors.teal, '/calendar'),
      _QuickAction('Documents', Icons.upload_file, Colors.orange, '/documents'),
      _QuickAction('Boss Chat', Icons.chat, Colors.deepPurple, '/chat'),
      _QuickAction('Tasks', Icons.task_alt, Colors.green, '/tasks'),
    ];

    return Wrap(
      spacing: 8,
      runSpacing: 8,
      children: actions.map((action) {
        return SizedBox(
          width: (MediaQuery.of(context).size.width - 48) / 3,
          child: OutlinedButton.icon(
            onPressed: () => context.push(action.route),
            icon: Icon(action.icon, size: 16, color: action.color),
            label: Text(
              action.label,
              style: theme.textTheme.labelSmall?.copyWith(color: action.color, fontSize: 10),
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
            ),
            style: OutlinedButton.styleFrom(
              side: BorderSide(color: action.color.withOpacity(0.3)),
              padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 6),
            ),
          ),
        );
      }).toList(),
    );
  }
}

class _QuickAction {
  final String label;
  final IconData icon;
  final Color color;
  final String route;

  const _QuickAction(this.label, this.icon, this.color, this.route);
}
