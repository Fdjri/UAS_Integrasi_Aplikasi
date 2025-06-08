import 'package:flutter/material.dart';

import 'home_page.dart';

class SuccessPage extends StatelessWidget {
  final Map service;  // Menerima data layanan yang dipilih
  final String total;  // Total harga yang dibayar

  // Constructor untuk menerima data layanan dan total harga
  SuccessPage({required this.service, required this.total});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Payment Success')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            // Menampilkan icon sukses
            Icon(
              Icons.celebration,
              size: 100,
              color: Colors.blue,
            ),
            SizedBox(height: 16),

            // Menampilkan pesan sukses
            Text(
              'Your Payment Is Successful',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 20),

            // Menampilkan detail pembayaran
            Text('Metode Pembayaran: ${service['payment_method']}'),
            SizedBox(height: 8),
            if (service['service_type'] == 'hotel') ...[
              Text('Tanggal Check-in: ${service['check_in_date']}'),
              Text('Tanggal Check-out: ${service['check_out_date']}'),
            ] else if (service['service_type'] == 'transportasi') ...[
              Text('Tanggal Perjalanan: ${service['departure_date']}'),
              Text('Tanggal Pulang: ${service['return_date']}'),
            ] else if (service['service_type'] == 'event') ...[
              Text('Jumlah Tiket: ${service['ticket_count']}'),
            ],
            SizedBox(height: 16),

            // Menampilkan total harga
            Text('Total: Rp $total', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),

            SizedBox(height: 30),

            // Tombol Kembali ke HomePage
            ElevatedButton(
              onPressed: () {
                // Mengarahkan kembali ke halaman utama
                Navigator.pushReplacement(
                  context,
                  MaterialPageRoute(builder: (context) => HomePage()),
                );
              },
              child: Text('Kembali'),
              style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
            ),
          ],
        ),
      ),
    );
  }
}
