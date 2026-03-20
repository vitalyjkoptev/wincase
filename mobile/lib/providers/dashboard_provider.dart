// =====================================================
// FILE: lib/providers/dashboard_provider.dart
// Dashboard state: KPI bar + 8 sections
// =====================================================

import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/api_client.dart';
import '../models/models.dart';
import 'auth_provider.dart';

// =====================================================
// DASHBOARD STATE
// =====================================================

class DashboardState {
  final KpiData? kpi;
  final Map<String, dynamic>? leads;
  final Map<String, dynamic>? finance;
  final Map<String, dynamic>? ads;
  final Map<String, dynamic>? social;
  final Map<String, dynamic>? seo;
  final bool isLoading;
  final String? error;

  const DashboardState({
    this.kpi,
    this.leads,
    this.finance,
    this.ads,
    this.social,
    this.seo,
    this.isLoading = false,
    this.error,
  });

  DashboardState copyWith({
    KpiData? kpi,
    Map<String, dynamic>? leads,
    Map<String, dynamic>? finance,
    Map<String, dynamic>? ads,
    Map<String, dynamic>? social,
    Map<String, dynamic>? seo,
    bool? isLoading,
    String? error,
  }) => DashboardState(
    kpi: kpi ?? this.kpi,
    leads: leads ?? this.leads,
    finance: finance ?? this.finance,
    ads: ads ?? this.ads,
    social: social ?? this.social,
    seo: seo ?? this.seo,
    isLoading: isLoading ?? this.isLoading,
    error: error,
  );
}

class DashboardNotifier extends StateNotifier<DashboardState> {
  final ApiClient _api;

  DashboardNotifier(this._api) : super(const DashboardState());

  /// Load full dashboard (all sections)
  Future<void> loadDashboard() async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final response = await _api.dio.get('/dashboard');
      final data = response.data['data'];

      state = state.copyWith(
        kpi: KpiData.fromJson(data['kpi']),
        leads: data['leads'] as Map<String, dynamic>?,
        finance: data['finance'] as Map<String, dynamic>?,
        ads: data['ads'] as Map<String, dynamic>?,
        social: data['social'] as Map<String, dynamic>?,
        seo: data['seo'] as Map<String, dynamic>?,
        isLoading: false,
      );
    } catch (e) {
      // API not ready — show empty dashboard
      state = state.copyWith(isLoading: false);
    }
  }

  /// Refresh KPI bar only (lightweight, 60s cache)
  Future<void> refreshKpi() async {
    try {
      final response = await _api.dio.get('/dashboard/kpi');
      state = state.copyWith(
        kpi: KpiData.fromJson(response.data['data']),
      );
    } catch (_) {}
  }

  /// Load single section
  Future<Map<String, dynamic>?> loadSection(String section) async {
    try {
      final response = await _api.dio.get('/dashboard/$section');
      return response.data['data'] as Map<String, dynamic>?;
    } catch (_) {
      return null;
    }
  }

  /// Update section from WebSocket event
  void updateFromWebSocket(String section, Map<String, dynamic> data) {
    switch (section) {
      case 'kpi':
        state = state.copyWith(kpi: KpiData.fromJson(data));
      case 'leads':
        state = state.copyWith(leads: data);
      case 'finance':
        state = state.copyWith(finance: data);
      case 'ads':
        state = state.copyWith(ads: data);
      case 'social':
        state = state.copyWith(social: data);
      case 'seo':
        state = state.copyWith(seo: data);
    }
  }
}

final dashboardProvider =
    StateNotifierProvider<DashboardNotifier, DashboardState>((ref) {
  return DashboardNotifier(ref.read(apiClientProvider));
});

// ---------------------------------------------------------------
// Аннотация (RU):
// DashboardProvider — состояние Dashboard.
// loadDashboard() → GET /dashboard (все секции).
// refreshKpi() → GET /dashboard/kpi (лёгкий refresh).
// updateFromWebSocket() — обновление от Reverb event.
// Секции: kpi, leads, finance, ads, social, seo.
// Файл: lib/providers/dashboard_provider.dart
// ---------------------------------------------------------------
