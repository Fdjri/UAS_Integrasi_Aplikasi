import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'dart:async'; // For countdown
import 'success_page.dart'; // Import SuccessPage
import 'package:http/http.dart' as http;
import 'dart:convert'; // For encoding the request body
import 'package:flutter_secure_storage/flutter_secure_storage.dart'; // For secure storage

class ProcessPage extends StatefulWidget {
  final Map service;  // Menerima data layanan
  final String total;  // Total harga yang harus dibayar
  final int bookingId; // Booking ID
  final String paymentMethod; // Selected payment method
  final int paymentId; // Payment ID

  // Constructor untuk menerima data layanan dan total harga
  ProcessPage({required this.service, required this.total, required this.bookingId, required this.paymentMethod, required this.paymentId, required ticketCount});

  @override
  _ProcessPageState createState() => _ProcessPageState();
}

class _ProcessPageState extends State<ProcessPage> {
  late int _countdown;  // Waktu countdown dalam detik
  late Timer _timer;  // Timer untuk countdown
  bool _isProcessingPayment = false; // To track if the payment request is in progress
  final _storage = FlutterSecureStorage();  // Instance of secure storage

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

  // Fungsi untuk mengonfirmasi pembayaran dan memperbarui status pembayaran
  Future<void> _handlePayment() async {
    setState(() {
      _isProcessingPayment = true; // Set loading state while processing payment
    });

    // Update payment status to 'paid' and booking status to 'confirmed'
    await _updatePaymentStatus(widget.paymentId);
    await _updateBookingStatus(widget.bookingId);

    // Navigate to the success page after payment is successful
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
          'method': widget.paymentMethod,  // Include the selected payment method
          'paid': true, // Mark as paid
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

  // Function to send PUT request to update the booking status
  Future<void> _updateBookingStatus(int bookingId) async {
    // Get the access token from secure storage
    String? accessToken = await _storage.read(key: 'access_token');
    if (accessToken == null || accessToken.isEmpty) {
      print('Access token is missing.');
      return;
    }

    final url = 'http://192.168.1.25:8000/api/customer/bookings/$bookingId/update-status'; // API endpoint to update booking status

    try {
      final response = await http.put(
        Uri.parse(url),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $accessToken', // Use the stored token here
        },
        body: json.encode({
          'status': 'confirmed', // Mark booking as confirmed
        }),
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final responseData = jsonDecode(response.body);
        print('Booking status updated: ${responseData['message']}');
      } else {
        final responseData = jsonDecode(response.body);
        print('Error updating booking status: ${responseData['message']}');
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal memperbarui status booking')));
      }
    } catch (error) {
      print('Error during booking status update: $error');
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Terjadi kesalahan saat memperbarui status booking')));
    }
  }

  @override
  void dispose() {
    _timer.cancel();  // Membatalkan timer saat halaman ditutup
    super.dispose();
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
            Text('Pembayaran sedang diproses...', style: TextStyle(fontSize: 18)),
            SizedBox(height: 10),
            Text('Sisa waktu: $_countdown detik', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),

            SizedBox(height: 20),

            Text('Metode Pembayaran: ${widget.paymentMethod ?? "Belum dipilih"}', style: TextStyle(fontSize: 16)),

            SizedBox(height: 16),

            // Menampilkan detail tanggal check-in/check-out
            if (widget.service['service_type'] == 'hotel') ...[
              Text('Tanggal Check-in: ${widget.service['check_in']}', style: TextStyle(fontSize: 16)),
              Text('Tanggal Check-out: ${widget.service['check_out']}', style: TextStyle(fontSize: 16)),
            ] else if (widget.service['service_type'] == 'transportasi') ...[
              Text('Tanggal Perjalanan: ${widget.service['date_pergi']}', style: TextStyle(fontSize: 16)),
              Text('Tanggal Pulang: ${widget.service['date_pulang']}', style: TextStyle(fontSize: 16)),
            ] else if (widget.service['service_type'] == 'event') ...[
              Text('Jumlah Tiket: ${widget.service['ticket_count']}', style: TextStyle(fontSize: 16)),
            ],

            SizedBox(height: 20),

            Text('Total: Rp ${widget.total}', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),

            Spacer(), // Pushes the button to the bottom of the screen

            ElevatedButton(
              onPressed: _isProcessingPayment ? null : _handlePayment, // Disable button while updating
              child: _isProcessingPayment
                  ? CircularProgressIndicator() // Show loading spinner while updating
                  : Text('Saya Sudah Bayar'),
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
