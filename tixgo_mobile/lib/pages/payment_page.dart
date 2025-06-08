import 'package:flutter/material.dart';
import 'process_page.dart';


class PaymentPage extends StatefulWidget {
  final Map service;  // Menerima data layanan yang dipilih
  final String total;  // Total harga yang harus dibayar

  // Constructor untuk menerima data layanan dan total harga
  PaymentPage({required this.service, required this.total});

  @override
  _PaymentPageState createState() => _PaymentPageState();
}

class _PaymentPageState extends State<PaymentPage> {
  // Menyimpan pilihan metode pembayaran
  String? _selectedPaymentMethod;

  // Fungsi untuk menangani pembayaran
  void _handlePayment() {
    if (_selectedPaymentMethod == null) {
      // Menampilkan pesan error jika metode pembayaran belum dipilih
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Pilih metode pembayaran terlebih dahulu')),
      );
      return;
    }

    // Jika sudah memilih metode pembayaran, tampilkan informasi
    print('Pembayaran untuk: ${widget.service['title']}');
    print('Metode Pembayaran: $_selectedPaymentMethod');
    // Arahkan ke halaman konfirmasi pembayaran atau status pembayaran selesai
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => ProcessPage(
          service: widget.service,
          total: widget.total,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Metode Pembayaran')),
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

            // Pilihan metode pembayaran
            Text('Pilih metode pembayaran:', style: TextStyle(fontSize: 18)),
            ListTile(
              title: Text('Metode A'),
              leading: Radio<String>(
                value: 'A',
                groupValue: _selectedPaymentMethod,
                onChanged: (value) {
                  setState(() {
                    _selectedPaymentMethod = value;
                  });
                },
              ),
            ),
            ListTile(
              title: Text('Metode B'),
              leading: Radio<String>(
                value: 'B',
                groupValue: _selectedPaymentMethod,
                onChanged: (value) {
                  setState(() {
                    _selectedPaymentMethod = value;
                  });
                },
              ),
            ),
            ListTile(
              title: Text('Metode C'),
              leading: Radio<String>(
                value: 'C',
                groupValue: _selectedPaymentMethod,
                onChanged: (value) {
                  setState(() {
                    _selectedPaymentMethod = value;
                  });
                },
              ),
            ),

            SizedBox(height: 20),

            // Menampilkan total harga
            Text('Total: Rp ${widget.total}', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),

            SizedBox(height: 20),

            // Tombol untuk melakukan pembayaran
            ElevatedButton(
              onPressed: _handlePayment,
              child: Text('Bayar'),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.blue,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
