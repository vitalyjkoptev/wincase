import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../theme/app_theme.dart';

class ApiService {
  static const String _base = WC.apiBase;
  static String? _token;

  static Map<String, String> get _headers => {
    'Accept': 'application/json',
    if (_token != null) 'Authorization': 'Bearer $_token',
  };

  static Future<void> loadToken() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
  }

  static Future<void> saveToken(String token) async {
    _token = token;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  static Future<void> clearToken() async {
    _token = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  static bool get isAuthenticated => _token != null;

  // POST /api/v1/auth/login — FormData (Apache/cPanel requires multipart)
  static Future<Map<String, dynamic>> login(String email, String password) async {
    final req = http.MultipartRequest('POST', Uri.parse('${WC.apiBaseV1}/auth/login'));
    req.headers.addAll({'Accept': 'application/json'});
    req.fields['email'] = email;
    req.fields['password'] = password;

    final streamed = await req.send();
    final body = await streamed.stream.bytesToString();
    final data = jsonDecode(body) as Map<String, dynamic>;

    if (streamed.statusCode == 200 && data['success'] == true) {
      await saveToken(data['data']['token']);
    }
    return data;
  }

  // POST /api/v1/auth/logout
  static Future<void> logout() async {
    try {
      final req = http.MultipartRequest('POST', Uri.parse('${WC.apiBaseV1}/auth/logout'));
      req.headers.addAll(_headers);
      await req.send();
    } catch (_) {}
    await clearToken();
  }

  // GET /api/v1/auth/me
  static Future<Map<String, dynamic>> getMe() async {
    final resp = await http.get(
      Uri.parse('${WC.apiBaseV1}/auth/me'),
      headers: _headers,
    );
    return jsonDecode(resp.body);
  }

  // POST — send as FormData (multipart)
  static Future<Map<String, dynamic>> _post(String url, Map<String, String> fields) async {
    final req = http.MultipartRequest('POST', Uri.parse(url));
    req.headers.addAll(_headers);
    req.fields.addAll(fields);
    final streamed = await req.send();
    final body = await streamed.stream.bytesToString();
    return jsonDecode(body) as Map<String, dynamic>;
  }

  // GET — standard
  static Future<Map<String, dynamic>> _get(String url) async {
    final resp = await http.get(Uri.parse(url), headers: _headers);
    return jsonDecode(resp.body) as Map<String, dynamic>;
  }

  // Client registration
  static Future<Map<String, dynamic>> register(Map<String, dynamic> data) async {
    final fields = data.map((k, v) => MapEntry(k, v.toString()));
    return _post('$_base/register', fields);
  }

  static Future<List<dynamic>> getRegistrations() async {
    final resp = await http.get(Uri.parse('$_base/registrations'), headers: _headers);
    return jsonDecode(resp.body);
  }

  static Future<Map<String, dynamic>> getRegistration(int id) async {
    return _get('$_base/registrations/$id');
  }
}
