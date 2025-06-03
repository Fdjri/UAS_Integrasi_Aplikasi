@extends('layouts.app')

@section('title', 'Payment Success')

@section('content')
<h1>Pembayaran Berhasil</h1>

<h3>Invoice Pembayaran</h3>
<table>
    <tr>
        <th>Booking ID</th>
        <td>{{ $payment['booking_id'] }}</td>
    </tr>
    <tr>
        <th>Payment ID</th>
        <td>{{ $payment['payment_id'] }}</td>
    </tr>
    <tr>
        <th>Metode Pembayaran</th>
        <td>{{ $payment['method'] }}</td>
    </tr>
    <tr>
        <th>Jumlah</th>
        <td>{{ number_format($payment['amount'], 2) }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{{ $payment['payment_status'] }}</td>
    </tr>
    <tr>
        <th>Tanggal Bayar</th>
        <td>{{ \Carbon\Carbon::parse($payment['paid_at'])->format('d-m-Y H:i') }}</td>
    </tr>
</table>

@if ($booking)
<h3>Detail Booking</h3>
<p>Service: {{ $booking['service']['title'] ?? '-' }}</p>
<p>Check In: {{ $booking['check_in'] ?? '-' }}</p>
<p>Check Out: {{ $booking['check_out'] ?? '-' }}</p>
<!-- Tambahkan detail lain sesuai kebutuhan -->
@endif

<a href="{{ route('customer.bookings.index') }}">Kembali ke Daftar Booking</a>
@endsection
