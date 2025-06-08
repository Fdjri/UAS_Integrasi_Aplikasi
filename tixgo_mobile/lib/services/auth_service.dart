import 'package:http/http.dart' as http;
import 'dart:convert';

class AuthService {
  final String baseUrl = 'http://127.0.0.1:8000/api';

  // Fungsi untuk login
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      body: {
        'email': email,
        'password': password,
      },
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body); // Parse dan kembalikan data dari response JSON
    } else {
      throw Exception('Gagal login: ${response.body}');
    }
  }

  // Fungsi untuk register
  Future<Map<String, dynamic>> register({
    required String name,
    required String username,
    required String email,
    required String password,
    required String confirmPassword,
  }) async {
    // Validasi password dan confirm password
    if (password != confirmPassword) {
      throw Exception('Kata sandi dan konfirmasi kata sandi tidak sama');
    }

    final response = await http.post(
      Uri.parse('$baseUrl/register/customer'),
      body: {
        'name': name,
        'username': username,
        'email': email,
        'password': password,
        'password_confirmation': confirmPassword, // Kirim konfirmasi password ke backend
      },
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body); // Parse dan kembalikan data dari response JSON
    } else {
      throw Exception('Gagal register: ${response.body}');
    }
  }
}
