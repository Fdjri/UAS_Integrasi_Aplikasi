import 'package:flutter/material.dart';
import 'booking_page.dart';  // Import halaman booking

class ServiceDetailPage extends StatelessWidget {
  final Map service;  // Menerima data layanan dari HomePage

  // Constructor untuk menerima data layanan
  ServiceDetailPage({required this.service});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Detail Layanan'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Gambar layanan
            Image.network(
              'http://127.0.0.1:8000/${service['photo_url']}',  // Sesuaikan dengan path gambar layanan
              width: double.infinity,
              height: 250,
              fit: BoxFit.cover,
            ),
            SizedBox(height: 16),

            // Judul layanan
            Text(
              service['title'],
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 8),

            // Alamat layanan
            Row(
              children: [
                Icon(Icons.location_on, color: Colors.grey),
                SizedBox(width: 8),
                Text(service['service_address']),
              ],
            ),
            SizedBox(height: 16),

            // Harga layanan
            Text(
              'Rp ${service['price']}',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 16),

            // Deskripsi layanan
            Text(
              service['description'] ?? 'No description available',
              style: TextStyle(fontSize: 16),
            ),
            Spacer(),

            // Tombol untuk melakukan pemesanan
            ElevatedButton(
              onPressed: () {
                // Mengarahkan pengguna ke halaman booking sesuai dengan layanan yang dipilih
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => BookingPage(service: service),
                  ),
                );
              },
              child: Text('Book Now'),
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
