// =====================================================
// WINCASE CRM — Staff/Employee Data Models
// All models for mobile employee app
// =====================================================

// =====================================================
// STAFF USER (current employee)
// =====================================================

class StaffUser {
  final int id;
  final String name;
  final String? role;
  final String? department;
  final String? position;
  final String? avatarUrl;
  final String? todaySchedule;

  StaffUser({
    required this.id,
    required this.name,
    this.role,
    this.department,
    this.position,
    this.avatarUrl,
    this.todaySchedule,
  });

  factory StaffUser.fromJson(Map<String, dynamic> json) => StaffUser(
    id: json['id'] ?? 0,
    name: json['name'] ?? '',
    role: json['role'],
    department: json['department'],
    position: json['position'],
    avatarUrl: json['avatar_url'],
    todaySchedule: json['today_schedule'],
  );
}

// =====================================================
// DASHBOARD STATS
// =====================================================

class StaffDashboardStats {
  final int myClients;
  final int activeCases;
  final int tasksToday;
  final int tasksOverdue;
  final int unreadTotal;
  final int expiringDocs;

  StaffDashboardStats({
    required this.myClients,
    required this.activeCases,
    required this.tasksToday,
    required this.tasksOverdue,
    required this.unreadTotal,
    required this.expiringDocs,
  });

  factory StaffDashboardStats.fromJson(Map<String, dynamic> json) => StaffDashboardStats(
    myClients: json['my_clients'] ?? 0,
    activeCases: json['active_cases'] ?? 0,
    tasksToday: json['tasks_today'] ?? 0,
    tasksOverdue: json['tasks_overdue'] ?? 0,
    unreadTotal: json['unread_total'] ?? 0,
    expiringDocs: json['expiring_docs'] ?? 0,
  );
}

// =====================================================
// DASHBOARD (full)
// =====================================================

class StaffDashboard {
  final StaffUser user;
  final StaffDashboardStats stats;
  final List<StaffTask> todayTasks;
  final List<StaffCase> activeCases;
  final List<InboxMessage> inbox;
  final List<StaffCase> deadlines;
  final bool isClockedIn;
  final String? clockInTime;

  StaffDashboard({
    required this.user,
    required this.stats,
    required this.todayTasks,
    required this.activeCases,
    required this.inbox,
    required this.deadlines,
    this.isClockedIn = false,
    this.clockInTime,
  });

  factory StaffDashboard.empty() => StaffDashboard(
    user: StaffUser(id: 0, name: ''),
    stats: StaffDashboardStats.fromJson({}),
    todayTasks: [], activeCases: [], inbox: [], deadlines: [],
  );

  factory StaffDashboard.fromJson(Map<String, dynamic> json) => StaffDashboard(
    user: StaffUser.fromJson(json['user'] ?? {}),
    stats: StaffDashboardStats.fromJson(json['stats'] ?? {}),
    todayTasks: (json['today_tasks'] as List? ?? []).map((e) => StaffTask.fromJson(e)).toList(),
    activeCases: (json['active_cases'] as List? ?? []).map((e) => StaffCase.fromJson(e)).toList(),
    inbox: (json['inbox'] as List? ?? []).map((e) => InboxMessage.fromJson(e)).toList(),
    deadlines: (json['deadlines'] as List? ?? []).map((e) => StaffCase.fromJson(e)).toList(),
    isClockedIn: json['time_tracking']?['is_clocked_in'] ?? false,
    clockInTime: json['time_tracking']?['clock_in_time'],
  );
}

// =====================================================
// INBOX MESSAGE (unified: boss + client + team)
// =====================================================

class InboxMessage {
  final int id;
  final String source; // 'staff' or 'client'
  final String type;
  final String fromName;
  final String? fromRole;
  final String? avatarUrl;
  final String preview;
  final int? caseId;
  final int? clientId;
  final DateTime createdAt;

  InboxMessage({
    required this.id,
    required this.source,
    required this.type,
    required this.fromName,
    this.fromRole,
    this.avatarUrl,
    required this.preview,
    this.caseId,
    this.clientId,
    required this.createdAt,
  });

  factory InboxMessage.fromJson(Map<String, dynamic> json) => InboxMessage(
    id: json['id'] ?? 0,
    source: json['source'] ?? 'staff',
    type: json['type'] ?? 'message',
    fromName: json['from_name'] ?? '',
    fromRole: json['from_role'],
    avatarUrl: json['avatar_url'],
    preview: json['preview'] ?? '',
    caseId: json['case_id'],
    clientId: json['client_id'],
    createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
  );

  bool get isFromBoss => fromRole == 'boss' || fromRole == 'admin_boss' || type == 'boss_chat';
  bool get isFromClient => source == 'client';
}

// =====================================================
// CLIENT
// =====================================================

class StaffClient {
  final int id;
  final String? firstName;
  final String? lastName;
  final String name;
  final String? email;
  final String? phone;
  final String? nationality;
  final String status;
  final String? city;
  final String? preferredLanguage;
  final DateTime? createdAt;
  final List<StaffCase>? cases;

  StaffClient({
    required this.id,
    this.firstName,
    this.lastName,
    required this.name,
    this.email,
    this.phone,
    this.nationality,
    required this.status,
    this.city,
    this.preferredLanguage,
    this.createdAt,
    this.cases,
  });

  factory StaffClient.fromJson(Map<String, dynamic> json) => StaffClient(
    id: json['id'] ?? 0,
    firstName: json['first_name'],
    lastName: json['last_name'],
    name: json['name'] ?? '${json['first_name'] ?? ''} ${json['last_name'] ?? ''}'.trim(),
    email: json['email'],
    phone: json['phone'],
    nationality: json['nationality'],
    status: json['status'] ?? 'active',
    city: json['city'],
    preferredLanguage: json['preferred_language'],
    createdAt: json['created_at'] != null ? DateTime.tryParse(json['created_at']) : null,
    cases: (json['cases'] as List?)?.map((e) => StaffCase.fromJson(e)).toList(),
  );

  String get displayName => name.isNotEmpty ? name : '$firstName $lastName'.trim();
  String get initials {
    final parts = displayName.split(' ');
    if (parts.length >= 2) return '${parts[0][0]}${parts[1][0]}'.toUpperCase();
    return displayName.isNotEmpty ? displayName[0].toUpperCase() : '?';
  }
}

// =====================================================
// CASE
// =====================================================

class StaffCase {
  final int id;
  final String caseNumber;
  final int? clientId;
  final String? serviceType;
  final String status;
  final String? priority;
  final DateTime? deadline;
  final int? progressPercentage;
  final StaffClient? client;
  final List<StaffTask>? tasks;

  StaffCase({
    required this.id,
    required this.caseNumber,
    this.clientId,
    this.serviceType,
    required this.status,
    this.priority,
    this.deadline,
    this.progressPercentage,
    this.client,
    this.tasks,
  });

  factory StaffCase.fromJson(Map<String, dynamic> json) => StaffCase(
    id: json['id'] ?? 0,
    caseNumber: json['case_number'] ?? '',
    clientId: json['client_id'],
    serviceType: json['service_type'],
    status: json['status'] ?? 'active',
    priority: json['priority'],
    deadline: json['deadline'] != null ? DateTime.tryParse(json['deadline']) : null,
    progressPercentage: json['progress_percentage'],
    client: json['client'] != null ? StaffClient.fromJson(json['client']) : null,
    tasks: (json['tasks'] as List?)?.map((e) => StaffTask.fromJson(e)).toList(),
  );

  bool get isUrgent => priority == 'urgent' || priority == 'high';
  bool get isOverdue => deadline != null && deadline!.isBefore(DateTime.now());
  int get pendingTasksCount => tasks?.where((t) => t.status != 'completed').length ?? 0;

  String get statusLabel => switch (status) {
    'active' => 'Active',
    'pending_docs' => 'Pending Docs',
    'submitted' => 'Submitted',
    'in_review' => 'In Review',
    'approved' => 'Approved',
    'completed' => 'Completed',
    'rejected' => 'Rejected',
    'on_hold' => 'On Hold',
    _ => status,
  };

  String get serviceLabel => switch (serviceType) {
    'karta_pobytu' => 'Karta Pobytu',
    'work_permit' => 'Work Permit',
    'permanent_residence' => 'Permanent Residence',
    'eu_blue_card' => 'EU Blue Card',
    'family_reunification' => 'Family Reunification',
    'pesel' => 'PESEL',
    'meldunek' => 'Meldunek',
    _ => serviceType ?? 'General',
  };
}

// =====================================================
// CASE DETAIL (with progress)
// =====================================================

class StaffCaseDetail {
  final StaffCase caseData;
  final CaseProgress progress;

  StaffCaseDetail({required this.caseData, required this.progress});

  factory StaffCaseDetail.fromJson(Map<String, dynamic> json) => StaffCaseDetail(
    caseData: StaffCase.fromJson(json['case'] ?? {}),
    progress: CaseProgress.fromJson(json['progress'] ?? {}),
  );
}

class CaseProgress {
  final String currentStage;
  final int stageIndex;
  final int totalStages;
  final int pct;
  final List<String> stages;

  CaseProgress({
    required this.currentStage,
    required this.stageIndex,
    required this.totalStages,
    required this.pct,
    required this.stages,
  });

  factory CaseProgress.fromJson(Map<String, dynamic> json) => CaseProgress(
    currentStage: json['current_stage'] ?? 'active',
    stageIndex: json['stage_index'] ?? 0,
    totalStages: json['total_stages'] ?? 5,
    pct: json['pct'] ?? 0,
    stages: (json['stages'] as List?)?.cast<String>() ?? [],
  );
}

// =====================================================
// TASK
// =====================================================

class StaffTask {
  final int id;
  final String title;
  final String? description;
  final String? type;
  final int? caseId;
  final int? clientId;
  final String priority;
  final String status;
  final DateTime? dueDate;
  final DateTime? completedAt;
  final StaffCase? caseData;

  StaffTask({
    required this.id,
    required this.title,
    this.description,
    this.type,
    this.caseId,
    this.clientId,
    required this.priority,
    required this.status,
    this.dueDate,
    this.completedAt,
    this.caseData,
  });

  factory StaffTask.fromJson(Map<String, dynamic> json) => StaffTask(
    id: json['id'] ?? 0,
    title: json['title'] ?? '',
    description: json['description'],
    type: json['type'],
    caseId: json['case_id'],
    clientId: json['client_id'],
    priority: json['priority'] ?? 'medium',
    status: json['status'] ?? 'pending',
    dueDate: json['due_date'] != null ? DateTime.tryParse(json['due_date']) : null,
    completedAt: json['completed_at'] != null ? DateTime.tryParse(json['completed_at']) : null,
    caseData: json['case'] != null ? StaffCase.fromJson(json['case']) : null,
  );

  bool get isOverdue => dueDate != null && dueDate!.isBefore(DateTime.now()) && status != 'completed';
  bool get isDueToday => dueDate != null && _isToday(dueDate!);
  bool get isCompleted => status == 'completed';

  String get priorityLabel => switch (priority) {
    'urgent' => 'Urgent',
    'high' => 'High',
    'medium' => 'Medium',
    'low' => 'Low',
    _ => priority,
  };

  String? get clientName => caseData?.client?.name;
  String? get caseNumber => caseData?.caseNumber;

  static bool _isToday(DateTime date) {
    final now = DateTime.now();
    return date.year == now.year && date.month == now.month && date.day == now.day;
  }
}

// =====================================================
// CHAT MESSAGE (staff internal + boss chat)
// =====================================================

class ChatMessage {
  final int id;
  final int senderId;
  final String senderName;
  final String? senderAvatar;
  final String body;
  final String type;
  final bool isEncrypted;
  final DateTime? readAt;
  final int? caseId;
  final int? clientId;
  final DateTime createdAt;

  ChatMessage({
    required this.id,
    required this.senderId,
    required this.senderName,
    this.senderAvatar,
    required this.body,
    required this.type,
    this.isEncrypted = false,
    this.readAt,
    this.caseId,
    this.clientId,
    required this.createdAt,
  });

  factory ChatMessage.fromJson(Map<String, dynamic> json) => ChatMessage(
    id: json['id'] ?? 0,
    senderId: json['sender_id'] ?? json['sender']?['id'] ?? 0,
    senderName: json['sender']?['name'] ?? json['sender_name'] ?? '',
    senderAvatar: json['sender']?['avatar_url'],
    body: json['body'] ?? '',
    type: json['type'] ?? 'message',
    isEncrypted: json['is_encrypted'] ?? false,
    readAt: json['read_at'] != null ? DateTime.tryParse(json['read_at']) : null,
    caseId: json['case_id'],
    clientId: json['client_id'],
    createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
  );

  bool get isRead => readAt != null;
}

// =====================================================
// CLIENT MESSAGE (from/to client — syncs to CRM)
// =====================================================

class ClientMessage {
  final int id;
  final int clientId;
  final int? caseId;
  final int? userId;
  final String? channel; // app, whatsapp, telegram, email, sms, phone
  final String direction; // inbound, outbound, internal
  final String? subject;
  final String body;
  final String? type;
  final DateTime? sentAt;
  final DateTime? readAt;
  final DateTime createdAt;

  ClientMessage({
    required this.id,
    required this.clientId,
    this.caseId,
    this.userId,
    this.channel,
    required this.direction,
    this.subject,
    required this.body,
    this.type,
    this.sentAt,
    this.readAt,
    required this.createdAt,
  });

  factory ClientMessage.fromJson(Map<String, dynamic> json) => ClientMessage(
    id: json['id'] ?? 0,
    clientId: json['client_id'] ?? 0,
    caseId: json['case_id'],
    userId: json['user_id'],
    channel: json['channel'],
    direction: json['direction'] ?? 'inbound',
    subject: json['subject'],
    body: json['body'] ?? '',
    type: json['type'],
    sentAt: json['sent_at'] != null ? DateTime.tryParse(json['sent_at']) : null,
    readAt: json['read_at'] != null ? DateTime.tryParse(json['read_at']) : null,
    createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
  );

  bool get isInbound => direction == 'inbound';
  bool get isUnread => readAt == null && isInbound;

  String get channelLabel => switch (channel) {
    'whatsapp' => 'WhatsApp',
    'telegram' => 'Telegram',
    'email' => 'Email',
    'sms' => 'SMS',
    'phone' => 'Phone',
    'app' => 'App',
    _ => channel ?? 'Chat',
  };
}

// =====================================================
// DOCUMENT
// =====================================================

class StaffDocument {
  final int id;
  final int clientId;
  final int? caseId;
  final String type;
  final String name;
  final String? status;
  final DateTime? expiresAt;
  final int? fileSize;
  final String? mimeType;
  final StaffClient? client;
  final DateTime createdAt;

  StaffDocument({
    required this.id,
    required this.clientId,
    this.caseId,
    required this.type,
    required this.name,
    this.status,
    this.expiresAt,
    this.fileSize,
    this.mimeType,
    this.client,
    required this.createdAt,
  });

  factory StaffDocument.fromJson(Map<String, dynamic> json) => StaffDocument(
    id: json['id'] ?? 0,
    clientId: json['client_id'] ?? 0,
    caseId: json['case_id'],
    type: json['type'] ?? 'other',
    name: json['name'] ?? '',
    status: json['status'],
    expiresAt: json['expires_at'] != null ? DateTime.tryParse(json['expires_at']) : null,
    fileSize: json['file_size'],
    mimeType: json['mime_type'],
    client: json['client'] != null ? StaffClient.fromJson(json['client']) : null,
    createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
  );

  bool get isExpiring => expiresAt != null && expiresAt!.difference(DateTime.now()).inDays <= 30;
  bool get isExpired => expiresAt != null && expiresAt!.isBefore(DateTime.now());

  String get typeLabel => switch (type) {
    'passport' => 'Passport',
    'visa' => 'Visa',
    'residence_card' => 'Karta Pobytu',
    'work_permit' => 'Work Permit',
    'pesel' => 'PESEL',
    'meldunek' => 'Meldunek',
    'contract' => 'Contract',
    'bank_statement' => 'Bank Statement',
    _ => type,
  };
}

// =====================================================
// CALENDAR EVENT
// =====================================================

class CalendarEvent {
  final String id;
  final String title;
  final DateTime date;
  final String type; // task, deadline, hearing
  final String? priority;
  final int? entityId;
  final String? caseNumber;
  final String? clientName;
  final String? location;
  final String? time;

  CalendarEvent({
    required this.id,
    required this.title,
    required this.date,
    required this.type,
    this.priority,
    this.entityId,
    this.caseNumber,
    this.clientName,
    this.location,
    this.time,
  });

  factory CalendarEvent.fromJson(Map<String, dynamic> json) => CalendarEvent(
    id: json['id']?.toString() ?? '',
    title: json['title'] ?? '',
    date: DateTime.parse(json['date'] ?? DateTime.now().toIso8601String()),
    type: json['type'] ?? 'task',
    priority: json['priority'],
    entityId: json['entity_id'],
    caseNumber: json['case_number'],
    clientName: json['client_name'],
    location: json['location'],
    time: json['time'],
  );

  bool get isToday {
    final now = DateTime.now();
    return date.year == now.year && date.month == now.month && date.day == now.day;
  }
}

// =====================================================
// TIME TRACKING
// =====================================================

class TimeEntry {
  final int id;
  final DateTime clockIn;
  final DateTime? clockOut;
  final double? hoursWorked;
  final String type;
  final String? notes;

  TimeEntry({
    required this.id,
    required this.clockIn,
    this.clockOut,
    this.hoursWorked,
    required this.type,
    this.notes,
  });

  factory TimeEntry.fromJson(Map<String, dynamic> json) => TimeEntry(
    id: json['id'] ?? 0,
    clockIn: DateTime.parse(json['clock_in']),
    clockOut: json['clock_out'] != null ? DateTime.tryParse(json['clock_out']) : null,
    hoursWorked: json['hours_worked']?.toDouble(),
    type: json['type'] ?? 'regular',
    notes: json['notes'],
  );

  bool get isOpen => clockOut == null;
}

// =====================================================
// CONVERSATION (team chat list)
// =====================================================

class TeamConversation {
  final int partnerId;
  final String partnerName;
  final String? partnerAvatar;
  final String? partnerRole;
  final String? partnerDepartment;
  final DateTime lastMessageAt;
  final int unreadCount;

  TeamConversation({
    required this.partnerId,
    required this.partnerName,
    this.partnerAvatar,
    this.partnerRole,
    this.partnerDepartment,
    required this.lastMessageAt,
    required this.unreadCount,
  });

  factory TeamConversation.fromJson(Map<String, dynamic> json) => TeamConversation(
    partnerId: json['partner']?['id'] ?? 0,
    partnerName: json['partner']?['name'] ?? '',
    partnerAvatar: json['partner']?['avatar_url'],
    partnerRole: json['partner']?['role'],
    partnerDepartment: json['partner']?['department'],
    lastMessageAt: DateTime.parse(json['last_message_at'] ?? DateTime.now().toIso8601String()),
    unreadCount: json['unread_count'] ?? 0,
  );
}
