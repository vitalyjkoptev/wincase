import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/api_client.dart';
import '../services/biometric_service.dart';

final apiClientProvider = Provider<ApiClient>((ref) => ApiClient());

class AuthState {
  final bool isAuthenticated;
  final bool isLoading;
  final String? userName;
  final String? userEmail;
  final String? userRole;      // Server role: boss, staff, user
  final String? avatarUrl;
  final String? error;
  final bool hasPasskey;

  const AuthState({
    this.isAuthenticated = false,
    this.isLoading = false,
    this.userName,
    this.userEmail,
    this.userRole,
    this.avatarUrl,
    this.error,
    this.hasPasskey = false,
  });

  bool get isBoss => userRole == 'boss';
  bool get isStaff => userRole == 'staff';
  bool get isClient => userRole == 'user';

  String get roleLabel => switch (userRole) {
    'boss' => 'Boss',
    'staff' => 'Staff',
    'user' => 'Client',
    _ => userRole ?? 'Unknown',
  };

  AuthState copyWith({
    bool? isAuthenticated,
    bool? isLoading,
    String? userName,
    String? userEmail,
    String? userRole,
    String? avatarUrl,
    String? error,
    bool? hasPasskey,
  }) => AuthState(
    isAuthenticated: isAuthenticated ?? this.isAuthenticated,
    isLoading: isLoading ?? this.isLoading,
    userName: userName ?? this.userName,
    userEmail: userEmail ?? this.userEmail,
    userRole: userRole ?? this.userRole,
    avatarUrl: avatarUrl ?? this.avatarUrl,
    error: error,
    hasPasskey: hasPasskey ?? this.hasPasskey,
  );
}

class AuthNotifier extends StateNotifier<AuthState> {
  final ApiClient _api;

  AuthNotifier(this._api) : super(const AuthState());

  Future<bool> login(String email, String password, String selectedMode) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final response = await _api.dio.post('/auth/login', data: {
        'email': email,
        'password': password,
      });

      final serverRole = response.data['data']['user']['role'] as String? ?? 'staff';
      final token = response.data['data']['token'] as String;

      // Validate role matches selected mode
      if (selectedMode == 'boss' && serverRole != 'boss') {
        try {
          await _api.setToken(token);
          await _api.dio.post('/auth/logout');
          await _api.clearToken();
        } catch (_) {}
        state = state.copyWith(isLoading: false, error: 'Access denied. Boss role required.');
        return false;
      }

      if (selectedMode == 'staff' && serverRole != 'staff') {
        try {
          await _api.setToken(token);
          await _api.dio.post('/auth/logout');
          await _api.clearToken();
        } catch (_) {}
        state = state.copyWith(isLoading: false, error: 'Access denied. Staff role required.');
        return false;
      }

      await _api.setToken(token);
      state = state.copyWith(
        isAuthenticated: true,
        isLoading: false,
        userName: response.data['data']['user']['name'],
        userEmail: email,
        userRole: serverRole,
        avatarUrl: response.data['data']['user']['avatar_url'],
      );
      return true;
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? e.response?.data?['errors']?['email']?[0] ?? 'Connection error';
      state = state.copyWith(isLoading: false, error: msg.toString());
      return false;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }

  // Login with passkey token (after biometric auth)
  Future<bool> loginWithToken(String token, String selectedMode) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      await _api.setToken(token);
      final response = await _api.dio.get('/auth/me');
      final user = response.data['data'];
      final serverRole = user['role'] as String? ?? 'staff';

      if (selectedMode == 'boss' && serverRole != 'boss') {
        await _api.clearToken();
        state = state.copyWith(isLoading: false, error: 'Access denied. Boss role required.');
        return false;
      }
      if (selectedMode == 'staff' && serverRole != 'staff') {
        await _api.clearToken();
        state = state.copyWith(isLoading: false, error: 'Access denied. Staff role required.');
        return false;
      }

      state = state.copyWith(
        isAuthenticated: true,
        isLoading: false,
        userName: user['name'],
        userEmail: user['email'],
        userRole: serverRole,
        avatarUrl: user['avatar_url'],
      );
      return true;
    } catch (e) {
      await _api.clearToken();
      state = state.copyWith(isLoading: false, error: 'Session expired. Please log in again.');
      return false;
    }
  }

  // Register — only for 'user' (client) role
  Future<bool> register(String name, String email, String password, String? phone) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final response = await _api.dio.post('/auth/register', data: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': password,
        if (phone != null && phone.isNotEmpty) 'phone': phone,
      });

      final token = response.data['data']['token'] as String;
      await _api.setToken(token);
      state = state.copyWith(
        isAuthenticated: true,
        isLoading: false,
        userName: response.data['data']['user']['name'],
        userEmail: email,
        userRole: 'user',
        avatarUrl: response.data['data']['user']['avatar_url'],
      );
      return true;
    } on DioException catch (e) {
      final msg = e.response?.data?['message'] ?? e.response?.data?['errors']?['email']?[0] ?? 'Registration failed';
      state = state.copyWith(isLoading: false, error: msg.toString());
      return false;
    } catch (e) {
      state = state.copyWith(isLoading: false, error: e.toString());
      return false;
    }
  }

  Future<void> logout({bool clearBiometric = true}) async {
    try { await _api.dio.post('/auth/logout'); } catch (_) {}
    await _api.clearToken();
    if (clearBiometric) await BiometricService.clear();
    state = const AuthState();
  }
}

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  return AuthNotifier(ref.read(apiClientProvider));
});
