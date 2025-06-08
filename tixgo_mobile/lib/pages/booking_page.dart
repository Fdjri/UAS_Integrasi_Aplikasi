import 'package:flutter/material.dart';
import 'payment_page.dart';

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

  @override
  void initState() {
    super.initState();
    _selectedBookingType = widget.service['service_type'];
  }

  // Fungsi untuk menangani pemesanan
  void _continueBooking() {
    // Menyimpan atau memproses pemesanan di sini
    print('Booking continued for: ${widget.service['title']}');

    // Bisa menambahkan logika untuk mengirim data pemesanan ke server, misalnya
    if (_selectedBookingType == 'hotel') {
      print('Check-in: ${_checkInController.text}, Check-out: ${_checkOutController.text}');
    } else if (_selectedBookingType == 'transportasi') {
      print('Departure Date: ${_departureDateController.text}, Return Date: ${_returnDateController.text}');
    } else if (_selectedBookingType == 'event') {
      print('Ticket Count: ${_ticketCountController.text}');
    }
    // Arahkan ke halaman pembayaran setelah pemesanan
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => PaymentPage(
          service: widget.service,
          total: widget.service['price'], // Bisa menghitung total harga jika perlu
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Booking - ${widget.service['title']}')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Menampilkan foto layanan
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
              if (_returnDateController.text.isNotEmpty) ...[
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
              onPressed: _continueBooking,
              child: Text('Continue'),
              style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
            ),
          ],
        ),
      ),
    );
  }
}
