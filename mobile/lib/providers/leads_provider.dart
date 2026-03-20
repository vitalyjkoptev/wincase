// =====================================================
// FILE: lib/providers/leads_provider.dart
// Leads state: list, filter, CRUD, convert
// =====================================================

import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/api_client.dart';
import '../models/models.dart';
import 'auth_provider.dart';

// =====================================================
// LEADS STATE
// =====================================================

class LeadsState {
  final List<Lead> leads;
  final Lead? selectedLead;
  final bool isLoading;
  final String? error;
  final String? statusFilter;
  final String? sourceFilter;
  final int totalCount;

  const LeadsState({
    this.leads = const [],
    this.selectedLead,
    this.isLoading = false,
    this.error,
    this.statusFilter,
    this.sourceFilter,
    this.totalCount = 0,
  });

  LeadsState copyWith({
    List<Lead>? leads,
    Lead? selectedLead,
    bool? isLoading,
    String? error,
    String? statusFilter,
    String? sourceFilter,
    int? totalCount,
  }) => LeadsState(
    leads: leads ?? this.leads,
    selectedLead: selectedLead ?? this.selectedLead,
    isLoading: isLoading ?? this.isLoading,
    error: error,
    statusFilter: statusFilter ?? this.statusFilter,
    sourceFilter: sourceFilter ?? this.sourceFilter,
    totalCount: totalCount ?? this.totalCount,
  );
}

class LeadsNotifier extends StateNotifier<LeadsState> {
  final ApiClient _api;

  LeadsNotifier(this._api) : super(const LeadsState());

  /// Fetch leads list with optional filters
  Future<void> loadLeads({String? status, String? source}) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final params = <String, dynamic>{};
      if (status != null) params['status'] = status;
      if (source != null) params['source'] = source;

      final response = await _api.dio.get('/leads', queryParameters: params);
      final data = response.data['data'] as List;

      state = state.copyWith(
        leads: data.map((j) => Lead.fromJson(j)).toList(),
        isLoading: false,
        totalCount: response.data['meta']?['total'] ?? data.length,
        statusFilter: status,
        sourceFilter: source,
      );
    } catch (e) {
      // API not ready — show empty list
      state = state.copyWith(isLoading: false, leads: []);
    }
  }

  /// Fetch single lead details
  Future<void> loadLeadDetail(int id) async {
    state = state.copyWith(isLoading: true);
    try {
      final response = await _api.dio.get('/leads/$id');
      state = state.copyWith(
        selectedLead: Lead.fromJson(response.data['data']),
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
    }
  }

  /// Create new lead
  Future<bool> createLead(Map<String, dynamic> data) async {
    try {
      await _api.dio.post('/leads', data: data);
      await loadLeads(status: state.statusFilter, source: state.sourceFilter);
      return true;
    } catch (_) {
      return false;
    }
  }

  /// Update lead status
  Future<bool> updateStatus(int id, String status) async {
    try {
      await _api.dio.patch('/leads/$id', data: {'status': status});
      await loadLeads(status: state.statusFilter, source: state.sourceFilter);
      return true;
    } catch (_) {
      return false;
    }
  }

  /// Convert lead to client
  Future<bool> convertToClient(int id) async {
    try {
      await _api.dio.post('/leads/$id/convert');
      await loadLeads(status: state.statusFilter, source: state.sourceFilter);
      return true;
    } catch (_) {
      return false;
    }
  }

  void clearSelection() {
    state = state.copyWith(selectedLead: null);
  }
}

final leadsProvider =
    StateNotifierProvider<LeadsNotifier, LeadsState>((ref) {
  return LeadsNotifier(ref.read(apiClientProvider));
});

// ---------------------------------------------------------------
// Аннотация (RU):
// LeadsProvider — CRUD лидов.
// loadLeads() — GET /leads с фильтрами status/source.
// loadLeadDetail() — GET /leads/:id.
// createLead() — POST /leads.
// updateStatus() — PATCH /leads/:id (смена статуса).
// convertToClient() — POST /leads/:id/convert.
// Файл: lib/providers/leads_provider.dart
// ---------------------------------------------------------------
