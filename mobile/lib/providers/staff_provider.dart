// =====================================================
// WINCASE CRM — Staff Provider (Riverpod)
// Manages all staff-related state + API calls
// =====================================================

import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/api_client.dart';
import '../providers/auth_provider.dart';
import '../models/staff_models.dart';

// =====================================================
// STAFF DASHBOARD PROVIDER
// =====================================================

class StaffDashboardState {
  final StaffDashboard? dashboard;
  final bool isLoading;
  final String? error;

  const StaffDashboardState({this.dashboard, this.isLoading = false, this.error});

  StaffDashboardState copyWith({StaffDashboard? dashboard, bool? isLoading, String? error}) =>
    StaffDashboardState(
      dashboard: dashboard ?? this.dashboard,
      isLoading: isLoading ?? this.isLoading,
      error: error,
    );
}

class StaffDashboardNotifier extends StateNotifier<StaffDashboardState> {
  final ApiClient _api;
  StaffDashboardNotifier(this._api) : super(const StaffDashboardState());

  Future<void> load() async {
    state = state.copyWith(isLoading: true);
    try {
      final res = await _api.dio.get('/staff/dashboard');
      state = StaffDashboardState(
        dashboard: StaffDashboard.fromJson(res.data['data']),
      );
    } catch (e) {
      // API not ready — show empty dashboard
      state = StaffDashboardState(dashboard: StaffDashboard.empty());
    }
  }

  Future<void> clockIn() async {
    try {
      await _api.dio.post('/staff/time/clock-in');
      await load();
    } catch (_) {}
  }

  Future<void> clockOut() async {
    try {
      await _api.dio.post('/staff/time/clock-out');
      await load();
    } catch (_) {}
  }
}

final staffDashboardProvider = StateNotifierProvider<StaffDashboardNotifier, StaffDashboardState>((ref) {
  return StaffDashboardNotifier(ref.read(apiClientProvider));
});

// =====================================================
// MY CLIENTS PROVIDER
// =====================================================

class ClientsState {
  final List<StaffClient> clients;
  final bool isLoading;
  final String? error;
  final int total;
  final int currentPage;

  const ClientsState({
    this.clients = const [],
    this.isLoading = false,
    this.error,
    this.total = 0,
    this.currentPage = 1,
  });
}

class StaffClientsNotifier extends StateNotifier<ClientsState> {
  final ApiClient _api;
  StaffClientsNotifier(this._api) : super(const ClientsState());

  Future<void> load({String? search, String? status}) async {
    state = ClientsState(isLoading: true);
    try {
      final params = <String, dynamic>{'per_page': 50};
      if (search != null) params['search'] = search;
      if (status != null) params['status'] = status;

      final res = await _api.dio.get('/staff/clients', queryParameters: params);
      final data = res.data['data'];
      state = ClientsState(
        clients: (data['data'] as List).map((e) => StaffClient.fromJson(e)).toList(),
        total: data['total'] ?? 0,
        currentPage: data['current_page'] ?? 1,
      );
    } catch (e) {
      // API not ready — show empty list
      state = const ClientsState();
    }
  }

  Future<Map<String, dynamic>> getDetail(int clientId) async {
    final res = await _api.dio.get('/staff/clients/$clientId');
    return res.data['data'];
  }
}

final staffClientsProvider = StateNotifierProvider<StaffClientsNotifier, ClientsState>((ref) {
  return StaffClientsNotifier(ref.read(apiClientProvider));
});

// =====================================================
// MY CASES PROVIDER
// =====================================================

class CasesState {
  final List<StaffCase> cases;
  final bool isLoading;
  final String? error;

  const CasesState({this.cases = const [], this.isLoading = false, this.error});
}

class StaffCasesNotifier extends StateNotifier<CasesState> {
  final ApiClient _api;
  StaffCasesNotifier(this._api) : super(const CasesState());

  Future<void> load({String? status, String? search}) async {
    state = const CasesState(isLoading: true);
    try {
      final params = <String, dynamic>{'per_page': 50};
      if (status != null) params['status'] = status;
      if (search != null) params['search'] = search;

      final res = await _api.dio.get('/staff/cases', queryParameters: params);
      final data = res.data['data'];
      final list = data is List ? data : (data['data'] as List? ?? []);
      state = CasesState(
        cases: list.map((e) => StaffCase.fromJson(e)).toList(),
      );
    } catch (e) {
      // API not ready — show empty list
      state = const CasesState();
    }
  }

  Future<StaffCaseDetail> getDetail(int caseId) async {
    final res = await _api.dio.get('/staff/cases/$caseId');
    return StaffCaseDetail.fromJson(res.data['data']);
  }

  Future<void> addNote(int caseId, String body) async {
    await _api.dio.post('/staff/cases/$caseId/note', data: {'body': body});
  }

  Future<void> updateStatus(int caseId, String status) async {
    await _api.dio.post('/staff/cases/$caseId/status', data: {'status': status});
    await load();
  }
}

final staffCasesProvider = StateNotifierProvider<StaffCasesNotifier, CasesState>((ref) {
  return StaffCasesNotifier(ref.read(apiClientProvider));
});

// =====================================================
// MY TASKS PROVIDER
// =====================================================

class TasksState {
  final List<StaffTask> tasks;
  final bool isLoading;
  final String? error;

  const TasksState({this.tasks = const [], this.isLoading = false, this.error});

  List<StaffTask> get overdue => tasks.where((t) => t.isOverdue).toList();
  List<StaffTask> get dueToday => tasks.where((t) => t.isDueToday && !t.isOverdue).toList();
  List<StaffTask> get upcoming => tasks.where((t) => !t.isOverdue && !t.isDueToday).toList();
}

class StaffTasksNotifier extends StateNotifier<TasksState> {
  final ApiClient _api;
  StaffTasksNotifier(this._api) : super(const TasksState());

  Future<void> load({String? status, String? priority}) async {
    state = const TasksState(isLoading: true);
    try {
      final params = <String, dynamic>{'per_page': 100};
      if (status != null) params['status'] = status;
      if (priority != null) params['priority'] = priority;

      final res = await _api.dio.get('/staff/tasks', queryParameters: params);
      final data = res.data['data'];
      final list = data is List ? data : (data['data'] as List? ?? []);
      state = TasksState(
        tasks: list.map((e) => StaffTask.fromJson(e)).toList(),
      );
    } catch (e) {
      // API not ready — show empty list
      state = const TasksState();
    }
  }

  Future<void> completeTask(int taskId) async {
    await _api.dio.post('/staff/tasks/$taskId/complete');
    state = TasksState(
      tasks: state.tasks.map((t) => t.id == taskId
        ? StaffTask(id: t.id, title: t.title, priority: t.priority, status: 'completed',
            dueDate: t.dueDate, completedAt: DateTime.now(), caseData: t.caseData,
            description: t.description, type: t.type, caseId: t.caseId, clientId: t.clientId)
        : t).toList(),
    );
  }

  Future<void> updateStatus(int taskId, String status) async {
    await _api.dio.post('/staff/tasks/$taskId/status', data: {'status': status});
    await load();
  }
}

final staffTasksProvider = StateNotifierProvider<StaffTasksNotifier, TasksState>((ref) {
  return StaffTasksNotifier(ref.read(apiClientProvider));
});

// =====================================================
// BOSS CHAT PROVIDER
// =====================================================

class BossChatState {
  final List<ChatMessage> messages;
  final StaffUser? boss;
  final bool isLoading;
  final String? error;

  const BossChatState({this.messages = const [], this.boss, this.isLoading = false, this.error});
}

class BossChatNotifier extends StateNotifier<BossChatState> {
  final ApiClient _api;
  BossChatNotifier(this._api) : super(const BossChatState());

  Future<void> load() async {
    state = BossChatState(isLoading: true);
    try {
      final res = await _api.dio.get('/staff/boss-chat');
      final data = res.data['data'];
      final msgList = data['messages']?['data'] as List? ?? [];
      state = BossChatState(
        messages: msgList.map((e) => ChatMessage.fromJson(e)).toList(),
        boss: data['boss'] != null ? StaffUser.fromJson(data['boss']) : null,
      );
    } catch (e) {
      // API not ready — show empty chat
      state = const BossChatState();
    }
  }

  Future<void> send(String body, {int? caseId, int? clientId}) async {
    try {
      final data = <String, dynamic>{'body': body};
      if (caseId != null) data['case_id'] = caseId;
      if (clientId != null) data['client_id'] = clientId;

      final res = await _api.dio.post('/staff/boss-chat', data: data);
      final msg = ChatMessage.fromJson(res.data['data']);
      state = BossChatState(
        messages: [msg, ...state.messages],
        boss: state.boss,
      );
    } catch (e) {
      state = BossChatState(messages: state.messages, boss: state.boss, error: e.toString());
    }
  }
}

final bossChatProvider = StateNotifierProvider<BossChatNotifier, BossChatState>((ref) {
  return BossChatNotifier(ref.read(apiClientProvider));
});

// =====================================================
// CLIENT MESSAGES PROVIDER
// =====================================================

class ClientMessagesState {
  final List<ClientMessage> messages;
  final bool isLoading;

  const ClientMessagesState({this.messages = const [], this.isLoading = false});
}

class ClientMessagesNotifier extends StateNotifier<ClientMessagesState> {
  final ApiClient _api;
  ClientMessagesNotifier(this._api) : super(const ClientMessagesState());

  Future<void> load(int clientId) async {
    state = const ClientMessagesState(isLoading: true);
    try {
      final res = await _api.dio.get('/staff/clients/$clientId/messages');
      final data = res.data['data']['data'] as List? ?? [];
      state = ClientMessagesState(
        messages: data.map((e) => ClientMessage.fromJson(e)).toList(),
      );
      // Mark as read
      await _api.dio.post('/staff/clients/$clientId/messages/read');
    } catch (_) {
      state = const ClientMessagesState();
    }
  }

  Future<void> send(int clientId, String body, {String? channel, int? caseId}) async {
    final data = <String, dynamic>{'body': body};
    if (channel != null) data['channel'] = channel;
    if (caseId != null) data['case_id'] = caseId;

    final res = await _api.dio.post('/staff/clients/$clientId/messages', data: data);
    final msg = ClientMessage.fromJson(res.data['data']);
    state = ClientMessagesState(messages: [msg, ...state.messages]);
  }

  Future<void> logCall(int clientId, {String? notes, String? direction}) async {
    await _api.dio.post('/staff/clients/$clientId/call-log', data: {
      'notes': notes ?? 'Phone call',
      'direction': direction ?? 'outbound',
    });
    await load(clientId);
  }
}

final clientMessagesProvider = StateNotifierProvider<ClientMessagesNotifier, ClientMessagesState>((ref) {
  return ClientMessagesNotifier(ref.read(apiClientProvider));
});

// =====================================================
// CALENDAR PROVIDER
// =====================================================

class CalendarState {
  final List<CalendarEvent> events;
  final bool isLoading;

  const CalendarState({this.events = const [], this.isLoading = false});

  List<CalendarEvent> eventsForDay(DateTime day) =>
    events.where((e) => e.date.year == day.year && e.date.month == day.month && e.date.day == day.day).toList();
}

class CalendarNotifier extends StateNotifier<CalendarState> {
  final ApiClient _api;
  CalendarNotifier(this._api) : super(const CalendarState());

  Future<void> load({String? month}) async {
    state = const CalendarState(isLoading: true);
    try {
      final params = <String, dynamic>{};
      if (month != null) params['month'] = month;

      final res = await _api.dio.get('/staff/calendar', queryParameters: params);
      final data = res.data['data'] as List? ?? [];
      state = CalendarState(
        events: data.map((e) => CalendarEvent.fromJson(e)).toList(),
      );
    } catch (_) {
      state = const CalendarState();
    }
  }
}

final calendarProvider = StateNotifierProvider<CalendarNotifier, CalendarState>((ref) {
  return CalendarNotifier(ref.read(apiClientProvider));
});

// =====================================================
// DOCUMENTS PROVIDER
// =====================================================

class DocumentsState {
  final List<StaffDocument> documents;
  final bool isLoading;

  const DocumentsState({this.documents = const [], this.isLoading = false});
}

class DocumentsNotifier extends StateNotifier<DocumentsState> {
  final ApiClient _api;
  DocumentsNotifier(this._api) : super(const DocumentsState());

  Future<void> load({String? type, String? clientId, bool? expiring}) async {
    state = const DocumentsState(isLoading: true);
    try {
      final params = <String, dynamic>{'per_page': 50};
      if (type != null) params['type'] = type;
      if (clientId != null) params['client_id'] = clientId;
      if (expiring == true) params['expiring'] = '1';

      final res = await _api.dio.get('/staff/documents', queryParameters: params);
      final data = res.data['data']['data'] as List? ?? [];
      state = DocumentsState(
        documents: data.map((e) => StaffDocument.fromJson(e)).toList(),
      );
    } catch (_) {
      state = const DocumentsState();
    }
  }

  Future<void> requestFromClient(int clientId, String docType, {String? message, int? caseId}) async {
    await _api.dio.post('/staff/clients/$clientId/request-document', data: {
      'document_type': docType,
      'message': message,
      'case_id': caseId,
    });
  }

  Future<bool> uploadDocument({
    required int clientId,
    required String filePath,
    String? type,
    String? name,
    int? caseId,
  }) async {
    try {
      final formData = {
        'client_id': clientId.toString(),
        if (type != null) 'type': type,
        if (name != null) 'name': name,
        if (caseId != null) 'case_id': caseId.toString(),
        'file': await MultipartFile.fromFile(filePath),
      };
      await _api.dio.post('/staff/documents/upload', data: FormData.fromMap(formData));
      await load();
      return true;
    } catch (_) {
      return false;
    }
  }
}

final documentsProvider = StateNotifierProvider<DocumentsNotifier, DocumentsState>((ref) {
  return DocumentsNotifier(ref.read(apiClientProvider));
});

// =====================================================
// PROFILE PROVIDER
// =====================================================

class ProfileState {
  final Map<String, dynamic>? data;
  final bool isLoading;

  const ProfileState({this.data, this.isLoading = false});
}

class ProfileNotifier extends StateNotifier<ProfileState> {
  final ApiClient _api;
  ProfileNotifier(this._api) : super(const ProfileState());

  Future<void> load() async {
    state = const ProfileState(isLoading: true);
    try {
      final res = await _api.dio.get('/staff/profile');
      state = ProfileState(data: res.data['data']);
    } catch (_) {
      state = const ProfileState();
    }
  }

  Future<bool> updateProfile({String? phone, String? emergencyContact, String? emergencyPhone}) async {
    try {
      await _api.dio.put('/staff/profile', data: {
        if (phone != null) 'phone': phone,
        if (emergencyContact != null) 'emergency_contact': emergencyContact,
        if (emergencyPhone != null) 'emergency_phone': emergencyPhone,
      });
      await load();
      return true;
    } catch (_) {
      return false;
    }
  }
}

final profileProvider = StateNotifierProvider<ProfileNotifier, ProfileState>((ref) {
  return ProfileNotifier(ref.read(apiClientProvider));
});
