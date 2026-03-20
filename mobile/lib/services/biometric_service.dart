import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:local_auth/local_auth.dart';

class BiometricService {
  static final _auth = LocalAuthentication();
  static const _storage = FlutterSecureStorage();

  // Storage keys
  static const _keyToken = 'biometric_token';
  static const _keyEmail = 'biometric_email';
  static const _keyRole = 'biometric_role';

  /// Check if device supports biometrics
  static Future<bool> isAvailable() async {
    try {
      final canCheck = await _auth.canCheckBiometrics;
      final isSupported = await _auth.isDeviceSupported();
      return canCheck && isSupported;
    } catch (_) {
      return false;
    }
  }

  /// Get available biometric types (face, fingerprint, etc.)
  static Future<List<BiometricType>> getAvailableTypes() async {
    try {
      return await _auth.getAvailableBiometrics();
    } catch (_) {
      return [];
    }
  }

  /// Prompt biometric authentication
  static Future<bool> authenticate({String reason = 'Verify your identity'}) async {
    try {
      return await _auth.authenticate(
        localizedReason: reason,
        options: const AuthenticationOptions(
          stickyAuth: true,
          biometricOnly: false, // allow PIN as fallback
        ),
      );
    } catch (_) {
      return false;
    }
  }

  /// Save credentials after successful login
  static Future<void> saveCredentials({
    required String token,
    required String email,
    required String role,
  }) async {
    await _storage.write(key: _keyToken, value: token);
    await _storage.write(key: _keyEmail, value: email);
    await _storage.write(key: _keyRole, value: role);
  }

  /// Check if biometric login is set up
  static Future<bool> hasStoredCredentials() async {
    final token = await _storage.read(key: _keyToken);
    return token != null && token.isNotEmpty;
  }

  /// Get stored role for pre-selecting on login screen
  static Future<String?> getStoredRole() async {
    return await _storage.read(key: _keyRole);
  }

  /// Get stored email
  static Future<String?> getStoredEmail() async {
    return await _storage.read(key: _keyEmail);
  }

  /// Get stored token after biometric verification
  static Future<String?> getTokenAfterAuth() async {
    final ok = await authenticate(reason: 'Log in to WinCase');
    if (!ok) return null;
    return await _storage.read(key: _keyToken);
  }

  /// Clear stored credentials (logout)
  static Future<void> clear() async {
    await _storage.delete(key: _keyToken);
    await _storage.delete(key: _keyEmail);
    await _storage.delete(key: _keyRole);
  }
}
