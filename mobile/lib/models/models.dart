// =====================================================
// FILE: lib/models/models.dart
// All data models for WINCASE CRM Mobile
// =====================================================

// =====================================================
// KPI BAR (12 cards)
// =====================================================

class KpiData {
  final int todayLeads;
  final int activeCases;
  final double monthlyRevenue;
  final double avgResponseMin;
  final double adSpend7d;
  final int organicUsers7d;
  final int socialFollowers;
  final double conversionRate30d;
  final int pendingTasks;
  final int activeClients;
  final int posPending;
  final double monthlyTaxBurden;

  KpiData({
    required this.todayLeads,
    required this.activeCases,
    required this.monthlyRevenue,
    required this.avgResponseMin,
    required this.adSpend7d,
    required this.organicUsers7d,
    required this.socialFollowers,
    required this.conversionRate30d,
    required this.pendingTasks,
    required this.activeClients,
    required this.posPending,
    required this.monthlyTaxBurden,
  });

  factory KpiData.fromJson(Map<String, dynamic> json) => KpiData(
    todayLeads: json['today_leads'] ?? 0,
    activeCases: json['active_cases'] ?? 0,
    monthlyRevenue: (json['monthly_revenue'] ?? 0).toDouble(),
    avgResponseMin: (json['avg_response_min'] ?? 0).toDouble(),
    adSpend7d: (json['ad_spend_7d'] ?? 0).toDouble(),
    organicUsers7d: json['organic_users_7d'] ?? 0,
    socialFollowers: json['social_followers'] ?? 0,
    conversionRate30d: (json['conversion_rate_30d'] ?? 0).toDouble(),
    pendingTasks: json['pending_tasks'] ?? 0,
    activeClients: json['active_clients'] ?? 0,
    posPending: json['pos_pending'] ?? 0,
    monthlyTaxBurden: (json['monthly_tax_burden'] ?? 0).toDouble(),
  );
}

// =====================================================
// LEAD
// =====================================================

class Lead {
  final int id;
  final String name;
  final String? email;
  final String phone;
  final String source;
  final String status;
  final String? priority;
  final String? serviceType;
  final String? language;
  final int? assignedTo;
  final String? notes;
  final int? clientId;
  final DateTime createdAt;
  final DateTime? firstContactedAt;

  Lead({
    required this.id,
    required this.name,
    this.email,
    required this.phone,
    required this.source,
    required this.status,
    this.priority,
    this.serviceType,
    this.language,
    this.assignedTo,
    this.notes,
    this.clientId,
    required this.createdAt,
    this.firstContactedAt,
  });

  factory Lead.fromJson(Map<String, dynamic> json) => Lead(
    id: json['id'],
    name: json['name'] ?? '',
    email: json['email'],
    phone: json['phone'] ?? '',
    source: json['source'] ?? 'unknown',
    status: json['status'] ?? 'new',
    priority: json['priority'],
    serviceType: json['service_type'],
    language: json['language'],
    assignedTo: json['assigned_to'],
    notes: json['notes'],
    clientId: json['client_id'],
    createdAt: DateTime.parse(json['created_at']),
    firstContactedAt: json['first_contacted_at'] != null
        ? DateTime.parse(json['first_contacted_at'])
        : null,
  );

  bool get isConverted => clientId != null;
  bool get isHighPriority => priority == 'high' || priority == 'urgent';

  String get statusLabel => switch (status) {
    'new' => 'New',
    'contacted' => 'Contacted',
    'consultation' => 'Consultation',
    'contract' => 'Contract',
    'paid' => 'Paid',
    'rejected' => 'Rejected',
    'spam' => 'Spam',
    _ => status,
  };
}

// =====================================================
// POS TRANSACTION
// =====================================================

class PosTransaction {
  final int id;
  final String receiptNumber;
  final double amount;
  final double? vatAmount;
  final double? totalAmount;
  final String paymentMethod;
  final String status;
  final String? clientName;
  final String? clientPhone;
  final String? serviceType;
  final String? notes;
  final int? processedBy;
  final int? approvedBy;
  final DateTime createdAt;

  PosTransaction({
    required this.id,
    required this.receiptNumber,
    required this.amount,
    this.vatAmount,
    this.totalAmount,
    required this.paymentMethod,
    required this.status,
    this.clientName,
    this.clientPhone,
    this.serviceType,
    this.notes,
    this.processedBy,
    this.approvedBy,
    required this.createdAt,
  });

  factory PosTransaction.fromJson(Map<String, dynamic> json) => PosTransaction(
    id: json['id'],
    receiptNumber: json['receipt_number'] ?? '',
    amount: (json['amount'] ?? 0).toDouble(),
    vatAmount: json['vat_amount']?.toDouble(),
    totalAmount: json['total_amount']?.toDouble(),
    paymentMethod: json['payment_method'] ?? 'cash',
    status: json['status'] ?? 'received',
    clientName: json['client_name'],
    clientPhone: json['client_phone'],
    serviceType: json['service_type'],
    notes: json['notes'],
    processedBy: json['processed_by'],
    approvedBy: json['approved_by'],
    createdAt: DateTime.parse(json['created_at']),
  );

  bool get isPending => status == 'received' || status == 'under_review';
  bool get isApproved => status == 'approved' || status == 'invoiced';

  String get statusLabel => switch (status) {
    'received' => 'Received',
    'under_review' => 'Under Review',
    'approved' => 'Approved',
    'invoiced' => 'Invoiced',
    'rejected' => 'Rejected',
    'refunded' => 'Refunded',
    _ => status,
  };

  String get methodLabel => switch (paymentMethod) {
    'cash' => 'Cash',
    'card' => 'Card',
    'blik' => 'BLIK',
    'transfer' => 'Transfer',
    _ => paymentMethod,
  };
}

// =====================================================
// NOTIFICATION
// =====================================================

class AppNotification {
  final int id;
  final String title;
  final String body;
  final String type;
  final String? actionUrl;
  final bool isRead;
  final DateTime createdAt;

  AppNotification({
    required this.id,
    required this.title,
    required this.body,
    required this.type,
    this.actionUrl,
    required this.isRead,
    required this.createdAt,
  });

  factory AppNotification.fromJson(Map<String, dynamic> json) => AppNotification(
    id: json['id'],
    title: json['title'] ?? '',
    body: json['body'] ?? '',
    type: json['type'] ?? 'info',
    actionUrl: json['action_url'],
    isRead: json['is_read'] ?? false,
    createdAt: DateTime.parse(json['created_at']),
  );
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Все модели данных Flutter приложения.
// KpiData — 12 KPI карточек dashboard.
// Lead — лид с 14 полями, statusLabel, isConverted, isHighPriority.
// PosTransaction — POS транзакция, statusLabel, methodLabel, isPending.
// AppNotification — push-уведомление.
// Все модели с factory fromJson() для десериализации API ответов.
// Файл: lib/models/models.dart
// ---------------------------------------------------------------
