// =====================================================
// WINCASE CRM — Boss/Director Data Models
// Full access: all clients, workers, finances, multichat
// =====================================================

import 'staff_models.dart';

// =====================================================
// BOSS DASHBOARD
// =====================================================

class BossDashboardStats {
  final int totalClients;
  final int activeCases;
  final int totalWorkers;
  final int workersOnline;
  final double monthRevenue;
  final double monthExpenses;
  final int unreadMessages;
  final int tasksOverdue;
  final int expiringDocs;
  final int pendingPayments;
  final double conversionRate;
  final int newLeadsToday;

  BossDashboardStats({
    required this.totalClients,
    required this.activeCases,
    required this.totalWorkers,
    required this.workersOnline,
    required this.monthRevenue,
    required this.monthExpenses,
    required this.unreadMessages,
    required this.tasksOverdue,
    required this.expiringDocs,
    required this.pendingPayments,
    required this.conversionRate,
    required this.newLeadsToday,
  });

  factory BossDashboardStats.fromJson(Map<String, dynamic> json) => BossDashboardStats(
    totalClients: json['total_clients'] ?? 0,
    activeCases: json['active_cases'] ?? 0,
    totalWorkers: json['total_workers'] ?? 0,
    workersOnline: json['workers_online'] ?? 0,
    monthRevenue: (json['month_revenue'] ?? 0).toDouble(),
    monthExpenses: (json['month_expenses'] ?? 0).toDouble(),
    unreadMessages: json['unread_messages'] ?? 0,
    tasksOverdue: json['tasks_overdue'] ?? 0,
    expiringDocs: json['expiring_docs'] ?? 0,
    pendingPayments: json['pending_payments'] ?? 0,
    conversionRate: (json['conversion_rate'] ?? 0).toDouble(),
    newLeadsToday: json['new_leads_today'] ?? 0,
  );

  double get profit => monthRevenue - monthExpenses;
}

class BossDashboard {
  final StaffUser user;
  final BossDashboardStats stats;
  final List<WorkerSummary> workers;
  final List<InboxMessage> urgentMessages;
  final List<StaffCase> criticalCases;
  final List<RevenuePoint> revenueChart;

  BossDashboard({
    required this.user,
    required this.stats,
    required this.workers,
    required this.urgentMessages,
    required this.criticalCases,
    required this.revenueChart,
  });

  factory BossDashboard.empty() => BossDashboard(
    user: StaffUser(id: 0, name: 'Admin'),
    stats: BossDashboardStats.fromJson({}),
    workers: [], urgentMessages: [], criticalCases: [], revenueChart: [],
  );

  factory BossDashboard.fromJson(Map<String, dynamic> json) => BossDashboard(
    user: StaffUser.fromJson(json['user'] ?? {}),
    stats: BossDashboardStats.fromJson(json['stats'] ?? {}),
    workers: (json['workers'] as List? ?? []).map((e) => WorkerSummary.fromJson(e)).toList(),
    urgentMessages: (json['urgent_messages'] as List? ?? []).map((e) => InboxMessage.fromJson(e)).toList(),
    criticalCases: (json['critical_cases'] as List? ?? []).map((e) => StaffCase.fromJson(e)).toList(),
    revenueChart: (json['revenue_chart'] as List? ?? []).map((e) => RevenuePoint.fromJson(e)).toList(),
  );
}

// =====================================================
// WORKER SUMMARY (for boss view)
// =====================================================

class WorkerSummary {
  final int id;
  final String name;
  final String? avatarUrl;
  final String? position;
  final String? department;
  final bool isOnline;
  final bool isClockedIn;
  final int activeClients;
  final int activeCases;
  final int tasksOverdue;
  final int unreadMessages;
  final DateTime? lastActiveAt;

  WorkerSummary({
    required this.id,
    required this.name,
    this.avatarUrl,
    this.position,
    this.department,
    required this.isOnline,
    required this.isClockedIn,
    required this.activeClients,
    required this.activeCases,
    required this.tasksOverdue,
    required this.unreadMessages,
    this.lastActiveAt,
  });

  factory WorkerSummary.fromJson(Map<String, dynamic> json) => WorkerSummary(
    id: json['id'] ?? 0,
    name: json['name'] ?? '',
    avatarUrl: json['avatar_url'],
    position: json['position'],
    department: json['department'],
    isOnline: json['is_online'] ?? false,
    isClockedIn: json['is_clocked_in'] ?? false,
    activeClients: json['active_clients'] ?? 0,
    activeCases: json['active_cases'] ?? 0,
    tasksOverdue: json['tasks_overdue'] ?? 0,
    unreadMessages: json['unread_messages'] ?? 0,
    lastActiveAt: json['last_active_at'] != null ? DateTime.tryParse(json['last_active_at']) : null,
  );

  String get initials {
    final parts = name.split(' ');
    if (parts.length >= 2) return '${parts[0][0]}${parts[1][0]}'.toUpperCase();
    return name.isNotEmpty ? name[0].toUpperCase() : '?';
  }
}

// =====================================================
// REVENUE CHART POINT
// =====================================================

class RevenuePoint {
  final String label;
  final double amount;

  RevenuePoint({required this.label, required this.amount});

  factory RevenuePoint.fromJson(Map<String, dynamic> json) => RevenuePoint(
    label: json['label'] ?? '',
    amount: (json['amount'] ?? 0).toDouble(),
  );
}

// =====================================================
// MULTICHAT CONVERSATION (unified across channels)
// =====================================================

class MultichatConversation {
  final int clientId;
  final String clientName;
  final String? clientAvatar;
  final String? clientPhone;
  final String? clientEmail;
  final String? assignedWorkerName;
  final int? assignedWorkerId;
  final String lastChannel; // whatsapp, telegram, email, sms, portal, facebook, instagram, etc.
  final String lastMessage;
  final DateTime lastMessageAt;
  final int unreadCount;
  final List<String> activeChannels;
  final String? caseNumber;
  final String? caseStatus;

  MultichatConversation({
    required this.clientId,
    required this.clientName,
    this.clientAvatar,
    this.clientPhone,
    this.clientEmail,
    this.assignedWorkerName,
    this.assignedWorkerId,
    required this.lastChannel,
    required this.lastMessage,
    required this.lastMessageAt,
    required this.unreadCount,
    required this.activeChannels,
    this.caseNumber,
    this.caseStatus,
  });

  factory MultichatConversation.fromJson(Map<String, dynamic> json) => MultichatConversation(
    clientId: json['client_id'] ?? 0,
    clientName: json['client_name'] ?? '',
    clientAvatar: json['client_avatar'],
    clientPhone: json['client_phone'],
    clientEmail: json['client_email'],
    assignedWorkerName: json['assigned_worker_name'],
    assignedWorkerId: json['assigned_worker_id'],
    lastChannel: json['last_channel'] ?? 'portal',
    lastMessage: json['last_message'] ?? '',
    lastMessageAt: DateTime.parse(json['last_message_at'] ?? DateTime.now().toIso8601String()),
    unreadCount: json['unread_count'] ?? 0,
    activeChannels: (json['active_channels'] as List?)?.cast<String>() ?? [],
    caseNumber: json['case_number'],
    caseStatus: json['case_status'],
  );

  String get initials {
    final parts = clientName.split(' ');
    if (parts.length >= 2) return '${parts[0][0]}${parts[1][0]}'.toUpperCase();
    return clientName.isNotEmpty ? clientName[0].toUpperCase() : '?';
  }

  bool get hasUnread => unreadCount > 0;

  static List<MultichatConversation> mockList() {
    final now = DateTime.now();
    return [
      MultichatConversation(clientId: 1, clientName: 'Olena Kovalenko', assignedWorkerName: 'Piotr Nowak', lastChannel: 'whatsapp', lastMessage: 'I sent the bank statement, please check', lastMessageAt: now.subtract(const Duration(minutes: 5)), unreadCount: 3, activeChannels: ['whatsapp', 'portal', 'email'], caseNumber: 'WC-2026-0421'),
      MultichatConversation(clientId: 2, clientName: 'Andrey Petrov', assignedWorkerName: 'Anna Kowalska', lastChannel: 'telegram', lastMessage: 'When is my appointment at the office?', lastMessageAt: now.subtract(const Duration(minutes: 22)), unreadCount: 1, activeChannels: ['telegram', 'portal'], caseNumber: 'WC-2026-0385'),
      MultichatConversation(clientId: 3, clientName: 'Maria Shevchenko', assignedWorkerName: 'Piotr Nowak', lastChannel: 'portal', lastMessage: 'Thank you for the update!', lastMessageAt: now.subtract(const Duration(hours: 1)), unreadCount: 0, activeChannels: ['portal', 'email'], caseNumber: 'WC-2026-0392'),
      MultichatConversation(clientId: 4, clientName: 'Viktor Bondarenko', lastChannel: 'email', lastMessage: 'Re: Missing documents for residence permit', lastMessageAt: now.subtract(const Duration(hours: 3)), unreadCount: 2, activeChannels: ['email', 'whatsapp', 'sms'], caseNumber: 'WC-2026-0410'),
      MultichatConversation(clientId: 5, clientName: 'Natalia Moroz', assignedWorkerName: 'Anna Kowalska', lastChannel: 'facebook', lastMessage: 'Hello, I want to ask about work permit', lastMessageAt: now.subtract(const Duration(hours: 5)), unreadCount: 1, activeChannels: ['facebook', 'portal'], caseNumber: 'WC-2026-0433'),
      MultichatConversation(clientId: 6, clientName: 'Dmytro Lysenko', lastChannel: 'viber', lastMessage: 'Document received, thanks!', lastMessageAt: now.subtract(const Duration(hours: 8)), unreadCount: 0, activeChannels: ['viber', 'whatsapp'], caseNumber: 'WC-2026-0405'),
      MultichatConversation(clientId: 7, clientName: 'Iryna Tkachenko', assignedWorkerName: 'Piotr Nowak', lastChannel: 'instagram', lastMessage: 'Can I send passport photo here?', lastMessageAt: now.subtract(const Duration(days: 1)), unreadCount: 1, activeChannels: ['instagram', 'portal'], caseNumber: 'WC-2026-0441'),
      MultichatConversation(clientId: 8, clientName: 'Sergiy Marchenko', lastChannel: 'sms', lastMessage: 'Reminder: appointment tomorrow 10:00', lastMessageAt: now.subtract(const Duration(days: 1, hours: 4)), unreadCount: 0, activeChannels: ['sms', 'whatsapp', 'portal'], caseNumber: 'WC-2026-0398'),
    ];
  }
}

// =====================================================
// MULTICHAT MESSAGE (across channels)
// =====================================================

class MultichatMessage {
  final int id;
  final int clientId;
  final String channel;
  final String direction; // inbound, outbound
  final String body;
  final String? senderName;
  final String? senderRole; // client, worker, boss
  final String? attachmentUrl;
  final String? attachmentType;
  final DateTime createdAt;
  final DateTime? readAt;

  MultichatMessage({
    required this.id,
    required this.clientId,
    required this.channel,
    required this.direction,
    required this.body,
    this.senderName,
    this.senderRole,
    this.attachmentUrl,
    this.attachmentType,
    required this.createdAt,
    this.readAt,
  });

  factory MultichatMessage.fromJson(Map<String, dynamic> json) => MultichatMessage(
    id: json['id'] ?? 0,
    clientId: json['client_id'] ?? 0,
    channel: json['channel'] ?? 'portal',
    direction: json['direction'] ?? 'inbound',
    body: json['body'] ?? '',
    senderName: json['sender_name'],
    senderRole: json['sender_role'],
    attachmentUrl: json['attachment_url'],
    attachmentType: json['attachment_type'],
    createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
    readAt: json['read_at'] != null ? DateTime.tryParse(json['read_at']) : null,
  );

  bool get isInbound => direction == 'inbound';
  bool get isRead => readAt != null;
  bool get hasAttachment => attachmentUrl != null;
}

// =====================================================
// FINANCE SUMMARY
// =====================================================

class FinanceSummary {
  final double totalRevenue;
  final double totalExpenses;
  final double netProfit;
  final int totalInvoices;
  final int paidInvoices;
  final int pendingInvoices;
  final int overdueInvoices;
  final double pendingAmount;
  final List<PaymentEntry> recentPayments;

  FinanceSummary({
    required this.totalRevenue,
    required this.totalExpenses,
    required this.netProfit,
    required this.totalInvoices,
    required this.paidInvoices,
    required this.pendingInvoices,
    required this.overdueInvoices,
    required this.pendingAmount,
    required this.recentPayments,
  });

  factory FinanceSummary.fromJson(Map<String, dynamic> json) => FinanceSummary(
    totalRevenue: (json['total_revenue'] ?? 0).toDouble(),
    totalExpenses: (json['total_expenses'] ?? 0).toDouble(),
    netProfit: (json['net_profit'] ?? 0).toDouble(),
    totalInvoices: json['total_invoices'] ?? 0,
    paidInvoices: json['paid_invoices'] ?? 0,
    pendingInvoices: json['pending_invoices'] ?? 0,
    overdueInvoices: json['overdue_invoices'] ?? 0,
    pendingAmount: (json['pending_amount'] ?? 0).toDouble(),
    recentPayments: (json['recent_payments'] as List? ?? []).map((e) => PaymentEntry.fromJson(e)).toList(),
  );
}

class PaymentEntry {
  final int id;
  final String clientName;
  final double amount;
  final String method;
  final String status;
  final DateTime date;

  PaymentEntry({
    required this.id,
    required this.clientName,
    required this.amount,
    required this.method,
    required this.status,
    required this.date,
  });

  factory PaymentEntry.fromJson(Map<String, dynamic> json) => PaymentEntry(
    id: json['id'] ?? 0,
    clientName: json['client_name'] ?? '',
    amount: (json['amount'] ?? 0).toDouble(),
    method: json['method'] ?? 'cash',
    status: json['status'] ?? 'pending',
    date: DateTime.parse(json['date'] ?? DateTime.now().toIso8601String()),
  );
}
