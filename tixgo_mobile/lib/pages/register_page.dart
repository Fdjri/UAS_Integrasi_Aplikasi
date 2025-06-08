import 'package:flutter/material.dart';
import '../services/auth_service.dart'; // Import AuthService

class RegisterPage extends StatefulWidget {
  @override
  _RegisterPageState createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  final _nameController = TextEditingController();
  final _usernameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  final AuthService _authService = AuthService(); // Inisialisasi AuthService

  // Fungsi untuk registrasi
  void _register() async {
    try {
      final response = await _authService.register(
        name: _nameController.text,
        username: _usernameController.text,
        email: _emailController.text,
        password: _passwordController.text,
        confirmPassword: _confirmPasswordController.text,
      );

      // Tangani response dari server setelah register
      print('Register berhasil: $response');
      // Arahkan ke halaman login setelah register
      Navigator.pop(context);
    } catch (e) {
      // Tangani error
      print('Register gagal: $e');
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Register gagal')));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Sign-up')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset('assets/register_logo.png', width: 50, height: 50),  // Sesuaikan logo
            SizedBox(height: 30),
            Text('Daftar', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
            SizedBox(height: 40),
            // Input Nama Lengkap
            TextField(
              controller: _nameController,
              decoration: InputDecoration(
                labelText: 'Nama Lengkap',
                border: UnderlineInputBorder(),
              ),
            ),
            // Input Username
            TextField(
              controller: _usernameController,
              decoration: InputDecoration(
                labelText: 'Username',
                border: UnderlineInputBorder(),
              ),
            ),
            // Input Email
            TextField(
              controller: _emailController,
              decoration: InputDecoration(
                labelText: 'Email',
                border: UnderlineInputBorder(),
              ),
            ),
            // Input Kata Sandi
            TextField(
              controller: _passwordController,
              obscureText: true,
              decoration: InputDecoration(
                labelText: 'Kata Sandi',
                border: UnderlineInputBorder(),
              ),
            ),
            // Input Konfirmasi Kata Sandi
            TextField(
              controller: _confirmPasswordController,
              obscureText: true,
              decoration: InputDecoration(
                labelText: 'Konfirmasi Kata Sandi',
                border: UnderlineInputBorder(),
              ),
            ),
            SizedBox(height: 20),
            // Tombol Sign Up
            ElevatedButton(
              onPressed: _register,
              child: Text('Sign Up'),
              style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
            ),
            SizedBox(height: 20),
            // Tombol untuk kembali ke halaman login
            TextButton(
              onPressed: () {
                Navigator.pop(context); // Kembali ke halaman login
              },
              child: Text('Sudah punya akun? Ayo login!'),
            ),
          ],
        ),
      ),
    );
  }
}
