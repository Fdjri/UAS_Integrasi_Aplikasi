import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';  // Import flutter_secure_storage

class AuthService {
  final String baseUrl = 'http://192.168.1.25:8080/api';
  final FlutterSecureStorage _secureStorage = FlutterSecureStorage();  // Instance of FlutterSecureStorage

  // Login function
  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login/customer'),
        body: {'email': email, 'password': password},
      );

      return _handleAuthResponse(response);
    } catch (e) {
      return _handleAuthError(e);
    }
  }

  // Register function
  Future<Map<String, dynamic>> register({
    required String name,
    required String username,
    required String email,
    required String password,
    required String confirmPassword,
  }) async {
    try {
      if (password != confirmPassword) {
        throw Exception('Password dan konfirmasi password tidak sama');
      }

      final response = await http.post(
        Uri.parse('$baseUrl/register/customer'),
        body: {
          'name': name,
          'username': username,
          'email': email,
          'password': password,
          'password_confirmation': confirmPassword,
        },
      );

      return _handleAuthResponse(response);
    } catch (e) {
      return _handleAuthError(e);
    }
  }

  // Handle authentication responses
  Map<String, dynamic> _handleAuthResponse(http.Response response) {
    final responseData = jsonDecode(response.body);
    
    if (response.statusCode == 200 || response.statusCode == 201) {
      final accessToken = responseData['access_token'];
      final userData = responseData['user'];
      
      saveToken(accessToken);
      saveUserData(userData);
      
      return responseData;
    } else {
      final errors = responseData['errors'] ?? {};
      final errorMsg = _parseRegisterErrors(errors) ?? 
                      responseData['message'] ?? 
                      'Terjadi kesalahan (${response.statusCode})';
      throw Exception(errorMsg);
    }
  }

  // Handle authentication errors
  Never _handleAuthError(dynamic e) {
    if (e is http.ClientException) {
      throw Exception('Koneksi jaringan gagal: ${e.message}');
    } else if (e is FormatException) {
      throw Exception('Format respons tidak valid');
    }
    throw e;
  }

  // Helper to parse registration errors
  String? _parseRegisterErrors(Map<String, dynamic> errors) {
    if (errors.isEmpty) return null;
    
    return errors.values
        .map((e) => e is List ? e.join(', ') : e.toString())
        .join('\n');
  }

  // Save token to secure storage
  Future<void> saveToken(String token) async {
    await _secureStorage.write(key: 'access_token', value: token);
  }

  // Save user data to secure storage
  Future<void> saveUserData(Map<String, dynamic> userData) async {
    await _secureStorage.write(key: 'user_data', value: json.encode(userData));
  }

  // Get stored token from secure storage
  Future<String?> getToken() async {
    return await _secureStorage.read(key: 'access_token');
  }

  // Get user data from secure storage
  Future<Map<String, dynamic>?> getUserData() async {
    final userString = await _secureStorage.read(key: 'user_data');
    return userString != null ? json.decode(userString) : null;
  }

  // Check if user is logged in by checking token in secure storage
  Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }

  // Logout functionality: remove token and user data from secure storage
  Future<void> logout() async {
    await _secureStorage.delete(key: 'access_token');
    await _secureStorage.delete(key: 'user_data');
    
    // Optional: Notify backend about logout
    try {
      final token = await getToken();
      if (token != null) {
        await http.post(
          Uri.parse('$baseUrl/logout'),
          headers: {'Authorization': 'Bearer $token'},
        ).timeout(const Duration(seconds: 2));
      }
    } catch (e) {
      // Ignore errors in logout API call
    }
  }

  // Generic API call with token handling
  Future<http.Response> authenticatedRequest(
    String method, 
    String endpoint, {
    Map<String, dynamic>? body,
  }) async {
    final token = await getToken();
    
    if (token == null || token.isEmpty) {
      throw Exception('Pengguna belum terotentikasi');
    }

    final url = Uri.parse('$baseUrl/$endpoint');
    final headers = {
      'Authorization': 'Bearer $token',
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    };

    switch (method.toLowerCase()) {
      case 'get':
        return await http.get(url, headers: headers);
      case 'post':
        return await http.post(
          url, 
          headers: headers, 
          body: body != null ? json.encode(body) : null
        );
      case 'put':
        return await http.put(
          url, 
          headers: headers, 
          body: body != null ? json.encode(body) : null
        );
      case 'delete':
        return await http.delete(url, headers: headers);
      default:
        throw Exception('Metode HTTP tidak didukung: $method');
    }
  }

  // Handle API errors globally
  Future<http.Response> safeApiCall(Future<http.Response> Function() request) async {
    try {
      final response = await request();
      
      if (response.statusCode == 401) {
        await logout();
        throw Exception('Sesi telah berakhir, silakan login kembali');
      }
      
      return response;
    } catch (e) {
      if (e is http.ClientException) {
        throw Exception('Gagal terhubung ke server: ${e.message}');
      }
      rethrow;
    }
  }
}
