import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'process_page.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';  // For encoding the request body
import 'package:flutter_secure_storage/flutter_secure_storage.dart'; // For secure storage

class PaymentPage extends StatefulWidget {
  final Map service;  // Menerima data layanan yang dipilih
  final String total;  // Total harga yang harus dibayar

  // Constructor untuk menerima data layanan dan total harga
  PaymentPage({required this.service, required this.total});

  @override
  _PaymentPageState createState() => _PaymentPageState();
}

class _PaymentPageState extends State<PaymentPage> {
  String? _selectedPaymentMethod;
  bool _isUpdating = false; // To track if the booking date update request is in progress
  final _storage = FlutterSecureStorage();  // Instance of secure storage

  // Fungsi untuk mengonfirmasi pembayaran dan memperbarui booking date
  Future<void> _handlePayment() async {
    if (_selectedPaymentMethod == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Pilih metode pembayaran terlebih dahulu')),
      );
      return;
    }

    // Get the payment ID and bookingId from service data
    final paymentId = widget.service['payment_id'];  // Access the payment_id from the service data
    final bookingId = widget.service['booking_id'];  // Access the booking_id from the service data

    if (paymentId == null || bookingId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Booking ID or Payment ID tidak ditemukan')),
      );
      return;
    }

    // Update payment status to 'pending'
    await _updatePaymentStatus(paymentId);

    // Pass all necessary data to ProcessPage
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => ProcessPage(
          service: widget.service,  // Passing the service data
          total: widget.total,  // Passing the total amount
          bookingId: bookingId,  // Pass the booking_id
          paymentId: paymentId,  // Pass the payment_id
          paymentMethod: _selectedPaymentMethod!, ticketCount: null,  // Pass the selected payment method
        ),
      ),
    );
  }

  // Function to send PUT request to update the payment status
  Future<void> _updatePaymentStatus(int paymentId) async {
    // Get the access token from secure storage
    String? accessToken = await _storage.read(key: 'access_token');
    if (accessToken == null || accessToken.isEmpty) {
      print('Access token is missing.');
      return;
    }

    final url = 'http://192.168.1.25:8000/api/customer/payments/$paymentId/update'; // API endpoint to update payment status

    try {
      final response = await http.put(
        Uri.parse(url),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $accessToken', // Use the stored token here
        },
        body: json.encode({
          'method': _selectedPaymentMethod,  // Include the selected payment method
        }),
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final responseData = jsonDecode(response.body);
        print('Payment status updated: ${responseData['message']}');
      } else {
        final responseData = jsonDecode(response.body);
        print('Error updating payment status: ${responseData['message']}');
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal memperbarui status pembayaran')));
      }
    } catch (error) {
      print('Error during payment status update: $error');
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Terjadi kesalahan saat memperbarui status pembayaran')));
    }
  }

  @override
  Widget build(BuildContext context) {
    final imageUrl = widget.service['photo_url'];

    return Scaffold(
      appBar: AppBar(title: Text('Metode Pembayaran')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CachedNetworkImage(
              imageUrl: imageUrl,
              width: double.infinity,
              height: 200,
              fit: BoxFit.cover,
              placeholder: (context, url) => CircularProgressIndicator(),
              errorWidget: (context, url, error) => Icon(Icons.error),
            ),
            SizedBox(height: 16),
            Text(
              widget.service['title'],
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 8),
            Text('Alamat: ${widget.service['service_address']}'),
            SizedBox(height: 8),
            Text('Harga: Rp ${widget.service['price']}'),
            SizedBox(height: 16),
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
            Text('Total: Rp ${widget.total}', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: _isUpdating ? null : _handlePayment,
              child: _isUpdating ? CircularProgressIndicator() : Text('Bayar'),
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
