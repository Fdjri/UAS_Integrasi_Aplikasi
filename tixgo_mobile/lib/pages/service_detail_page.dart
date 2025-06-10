import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart'; // Import CachedNetworkImage
import 'booking_page.dart';  // Import halaman booking

class ServiceDetailPage extends StatelessWidget {
  final Map service;  // Menerima data layanan dari HomePage

  // Constructor untuk menerima data layanan
  ServiceDetailPage({required this.service});

  @override
  Widget build(BuildContext context) {
    // Construct the correct image URL (ensure it's not repeated)
    final imageUrl = service['photo_url'];

    // Debugging: Check if description exists in the service data
    print(service['description']);

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
            CachedNetworkImage(
              imageUrl: imageUrl,  // Use the correct image URL from the backend
              width: double.infinity,
              height: 250,
              fit: BoxFit.cover,
              placeholder: (context, url) => CircularProgressIndicator(), // Placeholder during loading
              errorWidget: (context, url, error) => Icon(Icons.error), // Error widget in case of failure
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
                Text(service['service_address'] ?? 'No address available'),
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
              service['description'] != null && service['description'].isNotEmpty
                  ? service['description']
                  : 'No description available',
              style: TextStyle(fontSize: 16),
            ),
            Spacer(),

            // Tombol untuk melakukan pemesanan
            Align(
              alignment: Alignment.bottomCenter, // Centers the button at the bottom
              child: ElevatedButton(
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
                  padding: EdgeInsets.symmetric(horizontal: 100, vertical: 16), // Optional: Adjust padding for the button
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
