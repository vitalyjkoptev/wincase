// =====================================================
// WINCASE CRM — Boss/Director Providers (Riverpod)
// Full access to all data
// =====================================================

import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/boss_models.dart';
import '../models/staff_models.dart';
import '../services/api_client.dart';
import 'auth_provider.dart';

// =====================================================
// BOSS DASHBOARD
// =====================================================

class BossDashboardState {
  final BossDashboard? data;
  final bool isLoading;
  final String? error;
  BossDashboardState({this.data, this.isLoading = false, this.error});
}

class BossDashboardNotifier extends StateNotifier<BossDashboardState> {
  final ApiClient _api;
  BossDashboardNotifier(this._api) : super(BossDashboardState());

  Future<void> load() async {
    state = BossDashboardState(isLoading: true);
    try {
      final res = await _api.dio.get('/dashboard');
      state = BossDashboardState(data: BossDashboard.fromJson(res.data['data'] ?? res.data));
    } catch (e) {
      // API not ready yet — show empty dashboard
      state = BossDashboardState(data: BossDashboard.empty());
    }
  }
}

final bossDashboardProvider = StateNotifierProvider<BossDashboardNotifier, BossDashboardState>((ref) {
  return BossDashboardNotifier(ref.read(apiClientProvider));
});

// =====================================================
// MULTICHAT CONVERSATIONS (all clients, all channels)
// =====================================================

class MultichatState {
  final List<MultichatConversation> conversations;
  final bool isLoading;
  final String? error;
  final String? channelFilter;
  final String? searchQuery;
  MultichatState({this.conversations = const [], this.isLoading = false, this.error, this.channelFilter, this.searchQuery});
}

class MultichatNotifier extends StateNotifier<MultichatState> {
  final ApiClient _api;
  final String _basePath; // '/boss/multichat' or '/staff/multichat'

  MultichatNotifier(this._api, this._basePath) : super(MultichatState());

  Future<void> load({String? channel, String? search}) async {
    state = MultichatState(isLoading: true, channelFilter: channel, searchQuery: search);
    try {
      final params = <String, dynamic>{};
      if (channel != null) params['channel'] = channel;
      if (search != null && search.isNotEmpty) params['search'] = search;
      final res = await _api.dio.get(_basePath, queryParameters: params);
      final list = (res.data['data'] as List? ?? []).map((e) => MultichatConversation.fromJson(e)).toList();
      state = MultichatState(conversations: list, channelFilter: channel, searchQuery: search);
    } catch (e) {
      // API not ready — show mock conversations
      state = MultichatState(
        conversations: MultichatConversation.mockList(),
        channelFilter: channel,
        searchQuery: search,
      );
    }
  }

  void filterByChannel(String? channel) => load(channel: channel, search: state.searchQuery);
  void search(String query) => load(channel: state.channelFilter, search: query);
}

final bossMultichatProvider = StateNotifierProvider<MultichatNotifier, MultichatState>((ref) {
  return MultichatNotifier(ref.read(apiClientProvider), '/boss/multichat');
});

final staffMultichatProvider = StateNotifierProvider<MultichatNotifier, MultichatState>((ref) {
  return MultichatNotifier(ref.read(apiClientProvider), '/staff/multichat');
});

// =====================================================
// CHAT MESSAGES (for a specific client conversation)
// =====================================================

class ChatStreamState {
  final List<MultichatMessage> messages;
  final bool isLoading;
  final bool isSending;
  final String? error;
  final int? clientId;
  ChatStreamState({this.messages = const [], this.isLoading = false, this.isSending = false, this.error, this.clientId});
}

class ChatStreamNotifier extends StateNotifier<ChatStreamState> {
  final ApiClient _api;
  final String _basePath;

  ChatStreamNotifier(this._api, this._basePath) : super(ChatStreamState());

  Future<void> loadMessages(int clientId) async {
    state = ChatStreamState(isLoading: true, clientId: clientId);
    try {
      final res = await _api.dio.get('$_basePath/$clientId/messages');
      final msgs = (res.data['data'] as List? ?? []).map((e) => MultichatMessage.fromJson(e)).toList();
      state = ChatStreamState(messages: msgs, clientId: clientId);
    } catch (e) {
      // API not ready — show mock messages
      final now = DateTime.now();
      state = ChatStreamState(clientId: clientId, messages: [
        MultichatMessage(id: 1, clientId: clientId, channel: 'whatsapp', direction: 'inbound', body: 'Hello, I need help with my documents for residence card renewal.', senderName: 'Client', createdAt: now.subtract(const Duration(hours: 2))),
        MultichatMessage(id: 2, clientId: clientId, channel: 'portal', direction: 'outbound', body: 'Hi! Sure, please send me a scan of your current residence card and passport.', senderName: 'Operator', createdAt: now.subtract(const Duration(hours: 1, minutes: 50))),
        MultichatMessage(id: 3, clientId: clientId, channel: 'whatsapp', direction: 'inbound', body: 'Here is my passport scan. Residence card I will send tomorrow.', senderName: 'Client', createdAt: now.subtract(const Duration(hours: 1, minutes: 30))),
        MultichatMessage(id: 4, clientId: clientId, channel: 'portal', direction: 'outbound', body: 'Received, thank you. Please also prepare bank statement for last 3 months.', senderName: 'Operator', createdAt: now.subtract(const Duration(hours: 1)), readAt: now.subtract(const Duration(minutes: 55))),
        MultichatMessage(id: 5, clientId: clientId, channel: 'email', direction: 'inbound', body: 'I sent the bank statement to your email. Please check.', senderName: 'Client', createdAt: now.subtract(const Duration(minutes: 30))),
      ]);
    }
  }

  Future<void> sendMessage(int clientId, String body, String channel) async {
    state = ChatStreamState(messages: state.messages, isSending: true, clientId: clientId);
    try {
      final res = await _api.dio.post('$_basePath/$clientId/messages', data: {
        'body': body,
        'channel': channel,
      });
      final msg = MultichatMessage.fromJson(res.data['data'] ?? res.data);
      state = ChatStreamState(messages: [...state.messages, msg], clientId: clientId);
    } catch (e) {
      state = ChatStreamState(messages: state.messages, error: e.toString(), clientId: clientId);
    }
  }
}

final bossChatStreamProvider = StateNotifierProvider<ChatStreamNotifier, ChatStreamState>((ref) {
  return ChatStreamNotifier(ref.read(apiClientProvider), '/boss/multichat');
});

final staffChatStreamProvider = StateNotifierProvider<ChatStreamNotifier, ChatStreamState>((ref) {
  return ChatStreamNotifier(ref.read(apiClientProvider), '/staff/multichat');
});

// =====================================================
// BOSS: ALL WORKERS
// =====================================================

class WorkersState {
  final List<WorkerSummary> workers;
  final bool isLoading;
  final String? error;
  WorkersState({this.workers = const [], this.isLoading = false, this.error});
}

class WorkersNotifier extends StateNotifier<WorkersState> {
  final ApiClient _api;
  WorkersNotifier(this._api) : super(WorkersState());

  Future<void> load() async {
    state = WorkersState(isLoading: true);
    try {
      final res = await _api.dio.get('/boss/workers');
      final list = (res.data['data'] as List? ?? []).map((e) => WorkerSummary.fromJson(e)).toList();
      state = WorkersState(workers: list);
    } catch (e) {
      // API not ready — show empty list
      state = WorkersState();
    }
  }
}

final bossWorkersProvider = StateNotifierProvider<WorkersNotifier, WorkersState>((ref) {
  return WorkersNotifier(ref.read(apiClientProvider));
});

// =====================================================
// BOSS: ALL CLIENTS
// =====================================================

class BossClientsState {
  final List<StaffClient> clients;
  final bool isLoading;
  final String? error;
  BossClientsState({this.clients = const [], this.isLoading = false, this.error});
}

class BossClientsNotifier extends StateNotifier<BossClientsState> {
  final ApiClient _api;
  BossClientsNotifier(this._api) : super(BossClientsState());

  Future<void> load({String? search, String? status}) async {
    state = BossClientsState(isLoading: true);
    try {
      final params = <String, dynamic>{'per_page': 100};
      if (search != null && search.isNotEmpty) params['search'] = search;
      if (status != null) params['status'] = status;
      final res = await _api.dio.get('/boss/clients', queryParameters: params);
      final list = (res.data['data'] as List? ?? []).map((e) => StaffClient.fromJson(e)).toList();
      state = BossClientsState(clients: list);
    } catch (e) {
      // API not ready — show empty list
      state = BossClientsState();
    }
  }
}

final bossClientsProvider = StateNotifierProvider<BossClientsNotifier, BossClientsState>((ref) {
  return BossClientsNotifier(ref.read(apiClientProvider));
});

// =====================================================
// BOSS: FINANCES
// =====================================================

class FinanceState {
  final FinanceSummary? data;
  final bool isLoading;
  final String? error;
  FinanceState({this.data, this.isLoading = false, this.error});
}

class FinanceNotifier extends StateNotifier<FinanceState> {
  final ApiClient _api;
  FinanceNotifier(this._api) : super(FinanceState());

  Future<void> load() async {
    state = FinanceState(isLoading: true);
    try {
      final res = await _api.dio.get('/boss/finances');
      state = FinanceState(data: FinanceSummary.fromJson(res.data['data'] ?? res.data));
    } catch (e) {
      // API not ready — show empty state
      state = FinanceState();
    }
  }
}

final bossFinanceProvider = StateNotifierProvider<FinanceNotifier, FinanceState>((ref) {
  return FinanceNotifier(ref.read(apiClientProvider));
});
