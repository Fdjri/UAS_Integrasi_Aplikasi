import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'payment_page.dart';  // Import payment page for the next step
import 'package:http/http.dart' as http;
import 'dart:convert';  // For encoding the request body
import 'package:flutter_secure_storage/flutter_secure_storage.dart'; // Import for secure storage

class BookingPage extends StatefulWidget {
  final Map service;  // Menerima data layanan dari ServiceDetailPage

  BookingPage({required this.service});

  @override
  _BookingPageState createState() => _BookingPageState();
}

class _BookingPageState extends State<BookingPage> {
  final _checkInController = TextEditingController();
  final _checkOutController = TextEditingController();
  final _departureDateController = TextEditingController();
  final _returnDateController = TextEditingController();
  final _ticketCountController = TextEditingController();

  String? _selectedBookingType;
  String? _tripType;  // Track whether the trip is 'pergi saja' or 'pulang-pergi'

  final _storage = FlutterSecureStorage();  // Instance of secure storage

  @override
  void initState() {
    super.initState();
    _selectedBookingType = widget.service['service_type'];
  }

  // Fungsi untuk membuat pemesanan
  Future<void> _createBooking() async {
    final serviceId = widget.service['service_id'];
    final checkIn = _checkInController.text;
    final checkOut = _checkOutController.text;
    final ticketCount = _ticketCountController.text.isNotEmpty ? int.parse(_ticketCountController.text) : 1;
    final departureDate = _departureDateController.text;
    final returnDate = _returnDateController.text;

    // Membuat body untuk request
    final Map<String, dynamic> requestBody = {
      'service_id': serviceId,
      'check_in': checkIn.isNotEmpty ? checkIn : null,
      'check_out': checkOut.isNotEmpty ? checkOut : null,
      'ticket_count': ticketCount,
      'trip_type': _tripType,  // For transportasi, send 'pergi saja' or 'pulang-pergi'
      'date_pergi': departureDate.isNotEmpty ? departureDate : null,
      'date_pulang': returnDate.isNotEmpty ? returnDate : null,
    };

    final String url = 'http://192.168.1.25:8000/api/customer/bookings/init';  // Endpoint API

    // Retrieve the token from secure storage
    String? accessToken = await _storage.read(key: 'access_token');
    
    if (accessToken == null || accessToken.isEmpty) {
      print('Access token is missing.');
      return;
    }

    try {
      final response = await http.post(
        Uri.parse(url),
        body: json.encode(requestBody),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $accessToken', // Use the stored token here
        },
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 201) {
        final responseData = jsonDecode(response.body);
        print('Booking berhasil dibuat: ${responseData['message']}');

        // Navigasi ke halaman pembayaran
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => PaymentPage(
              service: {
                ...widget.service,  // Include all the existing service data
                'booking_id': responseData['booking_id'],
                'payment_id': responseData['payment_id'],  // Add booking_id here
              },
              total: responseData['total_price'].toString(),  // Convert total_price to String
            ),
          )
        );
      } else {
        // Handle errors here
        final responseData = jsonDecode(response.body);
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(responseData['message'] ?? 'Booking failed')));
      }
    } catch (error) {
      print('Error during booking: $error');
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error during booking')));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Booking - ${widget.service['title']}')),
      body: SingleChildScrollView(  // Use SingleChildScrollView to avoid overflow
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Menampilkan foto layanan
              CachedNetworkImage(
                imageUrl: widget.service['photo_url'],
                width: double.infinity,
                height: 200,
                fit: BoxFit.cover,
                placeholder: (context, url) => CircularProgressIndicator(),
                errorWidget: (context, url, error) => Icon(Icons.error),
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

              // Berdasarkan tipe layanan, tampilkan input yang sesuai
              if (_selectedBookingType == 'hotel') ...[
                TextField(
                  controller: _checkInController,
                  decoration: InputDecoration(
                    labelText: 'Tanggal Check-in',
                    border: UnderlineInputBorder(),
                  ),
                  readOnly: true,
                  onTap: () async {
                    DateTime? selectedDate = await showDatePicker(
                      context: context,
                      initialDate: DateTime.now(),
                      firstDate: DateTime(2020),
                      lastDate: DateTime(2100),
                    );
                    if (selectedDate != null) {
                      setState(() {
                        _checkInController.text = selectedDate.toLocal().toString().split(' ')[0];
                      });
                    }
                  },
                ),
                TextField(
                  controller: _checkOutController,
                  decoration: InputDecoration(
                    labelText: 'Tanggal Check-out',
                    border: UnderlineInputBorder(),
                  ),
                  readOnly: true,
                  onTap: () async {
                    DateTime? selectedDate = await showDatePicker(
                      context: context,
                      initialDate: DateTime.now(),
                      firstDate: DateTime(2020),
                      lastDate: DateTime(2100),
                    );
                    if (selectedDate != null) {
                      setState(() {
                        _checkOutController.text = selectedDate.toLocal().toString().split(' ')[0];
                      });
                    }
                  },
                ),
              ] else if (_selectedBookingType == 'transportasi') ...[
                // Radio Button for 'pergi saja' or 'pulang-pergi'
                Row(
                  children: [
                    Text('Pilih jenis perjalanan:'),
                    Radio<String>(
                      value: 'pergi',
                      groupValue: _tripType,
                      onChanged: (value) {
                        setState(() {
                          _tripType = value;
                          _returnDateController.clear(); // Clear return date if one-way is selected
                        });
                      },
                    ),
                    Text('Pergi Saja'),
                    Radio<String>(
                      value: 'pulang-pergi',
                      groupValue: _tripType,
                      onChanged: (value) {
                        setState(() {
                          _tripType = value;
                        });
                      },
                    ),
                    Text('Pulang Pergi'),
                  ],
                ),
                TextField(
                  controller: _departureDateController,
                  decoration: InputDecoration(
                    labelText: 'Tanggal Perjalanan',
                    border: UnderlineInputBorder(),
                  ),
                  readOnly: true,
                  onTap: () async {
                    DateTime? selectedDate = await showDatePicker(
                      context: context,
                      initialDate: DateTime.now(),
                      firstDate: DateTime(2020),
                      lastDate: DateTime(2100),
                    );
                    if (selectedDate != null) {
                      setState(() {
                        _departureDateController.text = selectedDate.toLocal().toString().split(' ')[0];
                      });
                    }
                  },
                ),
                if (_tripType == 'pulang-pergi') ...[
                  TextField(
                    controller: _returnDateController,
                    decoration: InputDecoration(
                      labelText: 'Tanggal Pulang',
                      border: UnderlineInputBorder(),
                    ),
                    readOnly: true,
                    onTap: () async {
                      DateTime? selectedDate = await showDatePicker(
                        context: context,
                        initialDate: DateTime.now(),
                        firstDate: DateTime(2020),
                        lastDate: DateTime(2100),
                      );
                      if (selectedDate != null) {
                        setState(() {
                          _returnDateController.text = selectedDate.toLocal().toString().split(' ')[0];
                        });
                      }
                    },
                  ),
                ],
              ] else if (_selectedBookingType == 'event') ...[
                TextField(
                  controller: _ticketCountController,
                  decoration: InputDecoration(
                    labelText: 'Jumlah Tiket',
                    border: UnderlineInputBorder(),
                  ),
                  keyboardType: TextInputType.number,
                ),
              ],
              SizedBox(height: 20),

              // Tombol untuk melanjutkan pemesanan
              ElevatedButton(
                onPressed: _createBooking,  // Call the booking API function
                child: Text('Continue'),
                style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
