// =====================================================
// FILE: lib/providers/pos_provider.dart
// POS state: transactions list, receive, approve, reject
// =====================================================

import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/api_client.dart';
import '../models/models.dart';
import 'auth_provider.dart';

// =====================================================
// POS STATE
// =====================================================

class PosState {
  final List<PosTransaction> transactions;
  final PosTransaction? selected;
  final bool isLoading;
  final String? error;
  final int pendingCount;

  const PosState({
    this.transactions = const [],
    this.selected,
    this.isLoading = false,
    this.error,
    this.pendingCount = 0,
  });

  PosState copyWith({
    List<PosTransaction>? transactions,
    PosTransaction? selected,
    bool? isLoading,
    String? error,
    int? pendingCount,
  }) => PosState(
    transactions: transactions ?? this.transactions,
    selected: selected ?? this.selected,
    isLoading: isLoading ?? this.isLoading,
    error: error,
    pendingCount: pendingCount ?? this.pendingCount,
  );
}

class PosNotifier extends StateNotifier<PosState> {
  final ApiClient _api;

  PosNotifier(this._api) : super(const PosState());

  /// Load all transactions (today by default)
  Future<void> loadTransactions({String? status}) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final params = <String, dynamic>{};
      if (status != null) params['status'] = status;

      final response = await _api.dio.get('/pos/history', queryParameters: params);
      final data = response.data['data'] as List;

      final txns = data.map((j) => PosTransaction.fromJson(j)).toList();

      state = state.copyWith(
        transactions: txns,
        isLoading: false,
        pendingCount: txns.where((t) => t.isPending).length,
      );
    } catch (e) {
      // API not ready — show empty list
      state = state.copyWith(isLoading: false, transactions: []);
    }
  }

  /// Load pending transactions only
  Future<void> loadPending() async {
    state = state.copyWith(isLoading: true);
    try {
      final response = await _api.dio.get('/pos/pending');
      final data = response.data['data'] as List;

      state = state.copyWith(
        transactions: data.map((j) => PosTransaction.fromJson(j)).toList(),
        isLoading: false,
        pendingCount: data.length,
      );
    } catch (e) {
      // API not ready — show empty list
      state = state.copyWith(isLoading: false, transactions: []);
    }
  }

  /// Receive new payment
  Future<bool> receivePayment(Map<String, dynamic> data) async {
    try {
      await _api.dio.post('/pos/receive', data: data);
      await loadTransactions();
      return true;
    } catch (_) {
      return false;
    }
  }

  /// Approve transaction (manager)
  Future<bool> approve(int id) async {
    try {
      await _api.dio.post('/pos/$id/approve');
      await loadTransactions();
      return true;
    } catch (_) {
      return false;
    }
  }

  /// Reject transaction (manager)
  Future<bool> reject(int id, String reason) async {
    try {
      await _api.dio.post('/pos/$id/reject', data: {'reason': reason});
      await loadTransactions();
      return true;
    } catch (_) {
      return false;
    }
  }

  /// Load single transaction detail
  Future<void> loadDetail(int id) async {
    state = state.copyWith(isLoading: true);
    try {
      final response = await _api.dio.get('/pos/$id');
      state = state.copyWith(
        selected: PosTransaction.fromJson(response.data['data']),
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  void clearSelection() {
    state = state.copyWith(selected: null);
  }
}

final posProvider =
    StateNotifierProvider<PosNotifier, PosState>((ref) {
  return PosNotifier(ref.read(apiClientProvider));
});

// ---------------------------------------------------------------
// Аннотация (RU):
// PosProvider — управление POS транзакциями.
// loadTransactions() — GET /pos/history.
// loadPending() — GET /pos/pending.
// receivePayment() — POST /pos/receive.
// approve() — POST /pos/:id/approve (менеджер).
// reject() — POST /pos/:id/reject с причиной.
// Файл: lib/providers/pos_provider.dart
// ---------------------------------------------------------------
