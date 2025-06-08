import 'package:flutter/material.dart';
import '../services/auth_service.dart'; // Import AuthService
import 'home_page.dart'; // Import halaman utama setelah login sukses
import 'register_page.dart'; // Import RegisterPage

class LoginPage extends StatefulWidget {
  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final AuthService _authService = AuthService(); // Inisialisasi AuthService

  // Fungsi untuk login
  void _login() async {
    try {
      final response = await _authService.login(
        _emailController.text,
        _passwordController.text,
      );

      // Cek apakah login berhasil, jika iya arahkan ke halaman utama
      print('Login berhasil: $response');
      
      // Arahkan ke halaman utama setelah login berhasil
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => HomePage()), // Ganti dengan halaman utama
      );
    } catch (e) {
      // Tangani error
      print('Login gagal: $e');
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Login gagal')));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset('assets/login_logo.png', width: 50, height: 50),  // Sesuaikan logo
            SizedBox(height: 30),
            Text('Masuk', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
            SizedBox(height: 40),
            // Input email
            TextField(
              controller: _emailController,
              decoration: InputDecoration(
                labelText: 'Email',
                border: UnderlineInputBorder(),
              ),
            ),
            // Input kata sandi
            TextField(
              controller: _passwordController,
              obscureText: true,
              decoration: InputDecoration(
                labelText: 'Kata Sandi',
                border: UnderlineInputBorder(),
              ),
            ),
            SizedBox(height: 20),
            // Tombol login
            ElevatedButton(
              onPressed: _login,
              child: Text('Masuk'),
              style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
            ),
            SizedBox(height: 20),
            // Tombol untuk pindah ke halaman register
            TextButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => RegisterPage()),
                );
              },
              child: Text('Belum punya akun? Daftar sekarang!'),
            ),
          ],
        ),
      ),
    );
  }
}
