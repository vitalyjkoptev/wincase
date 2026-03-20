import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import '../providers/auth_provider.dart';
import '../main.dart';

class AppNotification {
  final int id;
  final String title;
  final String body;
  final String type;
  final bool isRead;
  final DateTime createdAt;

  AppNotification({required this.id, required this.title, required this.body, required this.type, required this.isRead, required this.createdAt});

  factory AppNotification.fromJson(Map<String, dynamic> j) => AppNotification(
    id: j['id'] ?? 0,
    title: j['title'] ?? '',
    body: j['body'] ?? '',
    type: j['type'] ?? 'system',
    isRead: j['is_read'] == true,
    createdAt: DateTime.tryParse(j['created_at'] ?? '') ?? DateTime.now(),
  );
}

final notificationsProvider = FutureProvider<List<AppNotification>>((ref) async {
  final api = ref.read(apiClientProvider);
  try {
    final response = await api.dio.get('/notifications');
    final data = response.data['data'] as List;
    return data.map((j) => AppNotification.fromJson(j)).toList();
  } catch (_) {
    // Return mock data if API not ready
    return [
      AppNotification(id: 1, title: 'New Lead', body: 'Olena Kovalenko submitted registration form', type: 'lead', isRead: false, createdAt: DateTime.now().subtract(const Duration(minutes: 15))),
      AppNotification(id: 2, title: 'Payment Received', body: '2,000 PLN payment from Andrey Petrov', type: 'payment', isRead: false, createdAt: DateTime.now().subtract(const Duration(hours: 2))),
      AppNotification(id: 3, title: 'Case Updated', body: 'Case WC-2026-0421 status changed to Approved', type: 'case', isRead: true, createdAt: DateTime.now().subtract(const Duration(hours: 5))),
      AppNotification(id: 4, title: 'Task Overdue', body: 'Upload documents for client Maria Shevchenko', type: 'task', isRead: true, createdAt: DateTime.now().subtract(const Duration(days: 1))),
      AppNotification(id: 5, title: 'System Update', body: 'New version v4.1 available. Please update.', type: 'system', isRead: true, createdAt: DateTime.now().subtract(const Duration(days: 2))),
    ];
  }
});

class NotificationsScreen extends ConsumerWidget {
  const NotificationsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final asyncNotifs = ref.watch(notificationsProvider);
    final dateFmt = DateFormat('dd.MM HH:mm');

    return Scaffold(
      backgroundColor: WC.background,
      appBar: AppBar(
        title: const Text('Notifications'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.invalidate(notificationsProvider),
          ),
        ],
      ),
      body: asyncNotifs.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, _) => Center(child: Text('Error: $e')),
        data: (notifs) => notifs.isEmpty
            ? const Center(child: Text('No notifications', style: TextStyle(color: WC.textMuted)))
            : ListView.builder(
                itemCount: notifs.length,
                itemBuilder: (_, i) {
                  final n = notifs[i];
                  return Card(
                    margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                    color: n.isRead ? null : WC.navy.withValues(alpha: 0.04),
                    child: ListTile(
                      leading: CircleAvatar(
                        backgroundColor: _typeColor(n.type).withValues(alpha: 0.15),
                        child: Icon(_typeIcon(n.type), color: _typeColor(n.type), size: 20),
                      ),
                      title: Text(n.title, style: TextStyle(
                        fontWeight: n.isRead ? FontWeight.normal : FontWeight.bold, fontSize: 14,
                      )),
                      subtitle: Text(n.body, maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(fontSize: 12)),
                      trailing: Text(dateFmt.format(n.createdAt),
                        style: TextStyle(fontSize: 11, color: Colors.grey[500])),
                    ),
                  );
                },
              ),
      ),
    );
  }

  IconData _typeIcon(String type) => switch (type) {
    'lead' => Icons.person_add,
    'payment' => Icons.payment,
    'case' => Icons.folder,
    'task' => Icons.task_alt,
    'system' => Icons.info,
    _ => Icons.notifications,
  };

  Color _typeColor(String type) => switch (type) {
    'lead' => Colors.blue,
    'payment' => Colors.green,
    'case' => Colors.indigo,
    'task' => Colors.orange,
    'system' => Colors.grey,
    _ => Colors.blueGrey,
  };
}
