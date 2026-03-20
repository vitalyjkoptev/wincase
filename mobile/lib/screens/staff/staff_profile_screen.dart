// =====================================================
// WINCASE CRM -- Staff Profile Screen
// Avatar, personal info, schedule, KPI, stats, edit contact
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../providers/staff_provider.dart';
import '../../models/staff_models.dart';
import '../../providers/auth_provider.dart';

class StaffProfileScreen extends ConsumerStatefulWidget {
  const StaffProfileScreen({super.key});

  @override
  ConsumerState<StaffProfileScreen> createState() => _StaffProfileScreenState();
}

class _StaffProfileScreenState extends ConsumerState<StaffProfileScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(profileProvider.notifier).load());
  }

  void _editContact() {
    final data = ref.read(profileProvider).data;
    if (data == null) return;

    final phoneController = TextEditingController(text: data['phone']?.toString() ?? '');
    final emergencyController = TextEditingController(text: data['emergency_contact']?.toString() ?? '');

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (ctx) => Padding(
        padding: EdgeInsets.only(
          left: 24,
          right: 24,
          top: 24,
          bottom: MediaQuery.of(ctx).viewInsets.bottom + 24,
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Edit Contact Info', style: Theme.of(ctx).textTheme.titleMedium),
            const SizedBox(height: 16),
            TextField(
              controller: phoneController,
              decoration: const InputDecoration(
                labelText: 'Phone Number',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.phone),
              ),
              keyboardType: TextInputType.phone,
            ),
            const SizedBox(height: 12),
            TextField(
              controller: emergencyController,
              decoration: const InputDecoration(
                labelText: 'Emergency Contact',
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.emergency),
              ),
              keyboardType: TextInputType.phone,
            ),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: FilledButton(
                onPressed: () async {
                  Navigator.pop(ctx);
                  final ok = await ref.read(profileProvider.notifier).updateProfile(
                    phone: phoneController.text.trim().isNotEmpty ? phoneController.text.trim() : null,
                    emergencyContact: emergencyController.text.trim().isNotEmpty ? emergencyController.text.trim() : null,
                  );
                  if (context.mounted) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(content: Text(ok ? 'Contact info updated' : 'Update failed')),
                    );
                  }
                },
                child: const Text('Save'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(profileProvider);
    final authState = ref.watch(authProvider);
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    if (state.isLoading && state.data == null) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }

    final data = state.data ?? {};
    final name = data['name']?.toString() ?? authState.userName ?? 'Employee';
    final position = data['position']?.toString() ?? '';
    final department = data['department']?.toString() ?? '';
    final employeeId = data['employee_id']?.toString() ?? '';
    final avatarUrl = data['avatar_url']?.toString();
    final role = data['role']?.toString() ?? '';

    return Scaffold(
      appBar: AppBar(
        title: const Text('My Profile'),
        actions: [
          IconButton(
            icon: const Icon(Icons.edit_outlined),
            onPressed: _editContact,
            tooltip: 'Edit contact info',
          ),
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () => _showLogoutDialog(),
            tooltip: 'Logout',
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => ref.read(profileProvider.notifier).load(),
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              // --- Avatar + Name ---
              const SizedBox(height: 8),
              CircleAvatar(
                radius: 48,
                backgroundImage: avatarUrl != null ? NetworkImage(avatarUrl) : null,
                backgroundColor: colorScheme.primaryContainer,
                child: avatarUrl == null
                    ? Text(
                        name.isNotEmpty ? name[0].toUpperCase() : '?',
                        style: theme.textTheme.headlineLarge?.copyWith(
                          color: colorScheme.onPrimaryContainer,
                        ),
                      )
                    : null,
              ),
              const SizedBox(height: 12),
              Text(name, style: theme.textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.bold)),
              if (position.isNotEmpty)
                Text(position, style: theme.textTheme.bodyLarge?.copyWith(color: colorScheme.primary)),
              if (department.isNotEmpty)
                Text(department, style: theme.textTheme.bodyMedium?.copyWith(color: colorScheme.outline)),
              if (employeeId.isNotEmpty) ...[
                const SizedBox(height: 4),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                  decoration: BoxDecoration(
                    color: colorScheme.surfaceContainerHighest,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Text(
                    'ID: $employeeId',
                    style: theme.textTheme.labelSmall?.copyWith(color: colorScheme.outline),
                  ),
                ),
              ],
              const SizedBox(height: 24),

              // --- Personal Info Card ---
              _ProfileCard(
                title: 'Personal Information',
                icon: Icons.person,
                children: [
                  _ProfileRow(label: 'Full Name', value: name),
                  _ProfileRow(label: 'Email', value: data['email']?.toString() ?? authState.userEmail),
                  _ProfileRow(label: 'Phone', value: data['phone']?.toString()),
                  _ProfileRow(label: 'Role', value: role),
                  _ProfileRow(label: 'Emergency Contact', value: data['emergency_contact']?.toString()),
                ],
              ),
              const SizedBox(height: 12),

              // --- Work Schedule Card ---
              _ProfileCard(
                title: 'Work Schedule',
                icon: Icons.schedule,
                children: [
                  _ProfileRow(
                    label: 'Today',
                    value: data['today_schedule']?.toString() ?? 'Not set',
                  ),
                  _ProfileRow(
                    label: 'Work Hours',
                    value: data['work_hours']?.toString() ?? '9:00 - 17:00',
                  ),
                  _ProfileRow(
                    label: 'Days Off',
                    value: data['days_off']?.toString() ?? 'Sat, Sun',
                  ),
                ],
              ),
              const SizedBox(height: 12),

              // --- KPI Card ---
              _ProfileCard(
                title: 'KPI (This Month)',
                icon: Icons.trending_up,
                children: [
                  _KpiRow(
                    label: 'Tasks Completed',
                    value: _intVal(data['kpi']?['tasks_completed']),
                    target: _intVal(data['kpi']?['tasks_target']),
                    color: Colors.blue,
                  ),
                  _KpiRow(
                    label: 'Cases Processed',
                    value: _intVal(data['kpi']?['cases_processed']),
                    target: _intVal(data['kpi']?['cases_target']),
                    color: Colors.green,
                  ),
                  _KpiRow(
                    label: 'Avg Response Time',
                    value: _intVal(data['kpi']?['avg_response_min']),
                    target: _intVal(data['kpi']?['response_target_min']),
                    color: Colors.orange,
                    suffix: 'min',
                    lowerIsBetter: true,
                  ),
                ],
              ),
              const SizedBox(height: 12),

              // --- Stats Card ---
              _ProfileCard(
                title: 'Statistics',
                icon: Icons.bar_chart,
                children: [
                  _StatRow(
                    items: [
                      _StatPair('Clients', _intVal(data['stats']?['total_clients'])),
                      _StatPair('Cases', _intVal(data['stats']?['total_cases'])),
                      _StatPair('Completed', _intVal(data['stats']?['tasks_completed'])),
                    ],
                  ),
                ],
              ),
              const SizedBox(height: 32),
            ],
          ),
        ),
      ),
    );
  }

  int _intVal(dynamic value) => (value is int) ? value : (int.tryParse(value?.toString() ?? '') ?? 0);

  void _showLogoutDialog() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Logout'),
        content: const Text('Are you sure you want to logout?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
          FilledButton(
            onPressed: () {
              Navigator.pop(ctx);
              ref.read(authProvider.notifier).logout();
            },
            style: FilledButton.styleFrom(backgroundColor: Theme.of(ctx).colorScheme.error),
            child: const Text('Logout'),
          ),
        ],
      ),
    );
  }
}

// =====================================================
// PROFILE CARD
// =====================================================

class _ProfileCard extends StatelessWidget {
  final String title;
  final IconData icon;
  final List<Widget> children;

  const _ProfileCard({
    required this.title,
    required this.icon,
    required this.children,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return Card(
      elevation: 0,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: BorderSide(color: colorScheme.outlineVariant, width: 0.5),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(icon, size: 20, color: colorScheme.primary),
                const SizedBox(width: 8),
                Text(
                  title,
                  style: theme.textTheme.titleSmall?.copyWith(fontWeight: FontWeight.bold),
                ),
              ],
            ),
            const SizedBox(height: 12),
            ...children,
          ],
        ),
      ),
    );
  }
}

// =====================================================
// PROFILE ROW
// =====================================================

class _ProfileRow extends StatelessWidget {
  final String label;
  final String? value;

  const _ProfileRow({required this.label, this.value});

  @override
  Widget build(BuildContext context) {
    if (value == null || value!.isEmpty) return const SizedBox.shrink();
    final theme = Theme.of(context);
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 130,
            child: Text(
              label,
              style: theme.textTheme.bodySmall?.copyWith(color: theme.colorScheme.outline),
            ),
          ),
          Expanded(
            child: Text(value!, style: theme.textTheme.bodyMedium),
          ),
        ],
      ),
    );
  }
}

// =====================================================
// KPI ROW
// =====================================================

class _KpiRow extends StatelessWidget {
  final String label;
  final int value;
  final int target;
  final Color color;
  final String? suffix;
  final bool lowerIsBetter;

  const _KpiRow({
    required this.label,
    required this.value,
    required this.target,
    required this.color,
    this.suffix,
    this.lowerIsBetter = false,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final progress = target > 0
        ? (lowerIsBetter ? (target / (value > 0 ? value : 1)) : (value / target)).clamp(0.0, 1.0)
        : 0.0;
    final isOnTrack = lowerIsBetter ? value <= target : value >= target;
    final valueStr = suffix != null ? '$value $suffix' : '$value';
    final targetStr = suffix != null ? '$target $suffix' : '$target';

    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Expanded(child: Text(label, style: theme.textTheme.bodySmall)),
              Text(
                '$valueStr / $targetStr',
                style: theme.textTheme.labelSmall?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: isOnTrack ? Colors.green.shade700 : Colors.orange.shade700,
                ),
              ),
            ],
          ),
          const SizedBox(height: 4),
          ClipRRect(
            borderRadius: BorderRadius.circular(3),
            child: LinearProgressIndicator(
              value: progress,
              minHeight: 6,
              backgroundColor: color.withOpacity(0.1),
              color: isOnTrack ? Colors.green : color,
            ),
          ),
        ],
      ),
    );
  }
}

// =====================================================
// STAT ROW (horizontal stats)
// =====================================================

class _StatRow extends StatelessWidget {
  final List<_StatPair> items;

  const _StatRow({required this.items});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return Row(
      children: items.map((item) {
        return Expanded(
          child: Column(
            children: [
              Text(
                '${item.value}',
                style: theme.textTheme.headlineSmall?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: colorScheme.primary,
                ),
              ),
              Text(
                item.label,
                style: theme.textTheme.labelSmall?.copyWith(color: colorScheme.outline),
              ),
            ],
          ),
        );
      }).toList(),
    );
  }
}

class _StatPair {
  final String label;
  final int value;

  const _StatPair(this.label, this.value);
}
