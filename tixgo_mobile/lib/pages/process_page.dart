import 'package:flutter/material.dart';
import 'dart:async'; // Untuk countdown
import 'success_page.dart'; // Import halaman SuccessPage

class ProcessPage extends StatefulWidget {
  final Map service;  // Menerima data layanan
  final String total;  // Total harga yang harus dibayar

  // Constructor untuk menerima data layanan dan total harga
  ProcessPage({required this.service, required this.total});

  @override
  _ProcessPageState createState() => _ProcessPageState();
}

class _ProcessPageState extends State<ProcessPage> {
  late int _countdown;  // Waktu countdown dalam detik
  late Timer _timer;  // Timer untuk countdown

  @override
  void initState() {
    super.initState();
    _countdown = 30;  // Set countdown mulai dari 30 detik
    _startCountdown();  // Memulai countdown saat halaman dibuka
  }

  // Fungsi untuk memulai countdown
  void _startCountdown() {
    _timer = Timer.periodic(Duration(seconds: 1), (timer) {
      setState(() {
        if (_countdown > 0) {
          _countdown--;
        } else {
          _timer.cancel();  // Menghentikan timer saat countdown selesai
        }
      });
    });
  }

  // Fungsi untuk mengonfirmasi pembayaran selesai dan mengarahkan ke SuccessPage
  void _finishPayment() {
    // Simulasi pengiriman data pembayaran ke database
    print('Pembayaran selesai untuk: ${widget.service['title']}');
    // Arahkan ke halaman SuccessPage setelah pembayaran berhasil
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => SuccessPage(
          service: widget.service,
          total: widget.total,
        ),
      ),
    );
  }

  @override
  void dispose() {
    _timer.cancel();  // Membatalkan timer saat halaman ditutup
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Pemrosesan Pembayaran')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Menampilkan gambar layanan
            Image.network(
              'http://127.0.0.1:8000/${widget.service['photo_url']}',
              width: double.infinity,
              height: 200,
              fit: BoxFit.cover,
            ),
            SizedBox(height: 16),

            // Menampilkan detail layanan
            Text(
              widget.service['title'],
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 8),
            Text('Alamat: ${widget.service['service_address']}'),
            SizedBox(height: 8),
            Text('Harga: Rp ${widget.service['price']}'),
            SizedBox(height: 16),

            // Menampilkan countdown
            Text('Pembayaran sedang diproses...', style: TextStyle(fontSize: 18)),
            SizedBox(height: 10),
            Text('Sisa waktu: $_countdown detik', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),

            SizedBox(height: 20),

            // Menampilkan metode pembayaran
            Text('Metode Pembayaran: ${widget.service['payment_method'] ?? "Belum dipilih"}',
                style: TextStyle(fontSize: 16)),

            SizedBox(height: 16),

            // Menampilkan detail tanggal check-in/check-out
            if (widget.service['service_type'] == 'hotel') ...[
              Text('Tanggal Check-in: ${widget.service['check_in_date']}', style: TextStyle(fontSize: 16)),
              Text('Tanggal Check-out: ${widget.service['check_out_date']}', style: TextStyle(fontSize: 16)),
            ] else if (widget.service['service_type'] == 'transportasi') ...[
              Text('Tanggal Perjalanan: ${widget.service['departure_date']}', style: TextStyle(fontSize: 16)),
              Text('Tanggal Pulang: ${widget.service['return_date']}', style: TextStyle(fontSize: 16)),
            ] else if (widget.service['service_type'] == 'event') ...[
              Text('Jumlah Tiket: ${widget.service['ticket_count']}', style: TextStyle(fontSize: 16)),
            ],

            SizedBox(height: 20),

            // Menampilkan total harga
            Text('Total: Rp ${widget.total}', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),

            Spacer(),

            // Tombol untuk mengonfirmasi pembayaran selesai
            ElevatedButton(
              onPressed: _finishPayment,
              child: Text('Saya Sudah Bayar'),
              style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
            ),
          ],
        ),
      ),
    );
  }
}
