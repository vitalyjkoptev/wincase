// =====================================================
// FILE: lib/core/api_client.dart
// Dio HTTP client with Sanctum token auth + interceptors
// Apache/cPanel requires FormData (multipart) for POST/PUT/PATCH
// =====================================================

import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiClient {
  static const String baseUrl = 'https://crm.wincase.eu/api/v1';
  static const String tokenKey = 'auth_token';

  late final Dio dio;
  final FlutterSecureStorage _storage = const FlutterSecureStorage();

  ApiClient() {
    dio = Dio(BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 30),
      headers: {
        'Accept': 'application/json',
      },
    ));

    dio.interceptors.add(_AuthInterceptor(_storage));
    dio.interceptors.add(_FormDataInterceptor());
    dio.interceptors.add(_ErrorInterceptor());
    dio.interceptors.add(LogInterceptor(
      requestBody: true,
      responseBody: true,
      logPrint: (obj) => print('[API] $obj'),
    ));
  }

  // =====================================================
  // AUTH
  // =====================================================

  Future<void> setToken(String token) async {
    await _storage.write(key: tokenKey, value: token);
  }

  Future<void> clearToken() async {
    await _storage.delete(key: tokenKey);
  }

  Future<String?> getToken() async {
    return await _storage.read(key: tokenKey);
  }

  Future<bool> isAuthenticated() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }
}

// =====================================================
// AUTH INTERCEPTOR — auto-attach Bearer token
// =====================================================

class _AuthInterceptor extends Interceptor {
  final FlutterSecureStorage _storage;

  _AuthInterceptor(this._storage);

  @override
  Future<void> onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    final token = await _storage.read(key: ApiClient.tokenKey);
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    handler.next(options);
  }
}

// =====================================================
// FORMDATA INTERCEPTOR — convert Map data to FormData
// Apache/cPanel doesn't parse JSON request body,
// so all POST/PUT/PATCH must use multipart/form-data
// =====================================================

class _FormDataInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    final method = options.method.toUpperCase();
    if (['POST', 'PUT', 'PATCH', 'DELETE'].contains(method)) {
      if (options.data is Map<String, dynamic>) {
        options.data = FormData.fromMap(options.data as Map<String, dynamic>);
      } else if (options.data is Map) {
        options.data = FormData.fromMap(
          Map<String, dynamic>.from(options.data as Map),
        );
      }
    }
    handler.next(options);
  }
}

// =====================================================
// ERROR INTERCEPTOR — unified error handling
// =====================================================

class _ErrorInterceptor extends Interceptor {
  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    final statusCode = err.response?.statusCode;

    switch (statusCode) {
      case 401:
        // Token expired — redirect to login
        break;
      case 403:
        break;
      case 422:
        // Validation errors
        break;
      case 429:
        // Rate limited
        break;
      case 500:
        break;
    }

    handler.next(err);
  }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// ApiClient — HTTP клиент на Dio с Sanctum Bearer token.
// FlutterSecureStorage для хранения токена (Keychain iOS, Keystore Android).
// AuthInterceptor — автоматически добавляет Authorization header.
// ErrorInterceptor — обработка 401/403/422/429/500.
// Файл: lib/core/api_client.dart
// ---------------------------------------------------------------
