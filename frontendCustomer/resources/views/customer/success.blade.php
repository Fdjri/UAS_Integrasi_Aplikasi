@extends('customer.layouts.landing')

@section('title', 'Pembayaran Berhasil')

@section('content')
<style>
  .success-container {
    max-width: 600px;
    margin: 60px auto;
    background-color: #ccc;
    padding: 40px 20px;
    border-radius: 12px;
    text-align: center;
    font-family: 'Poppins', sans-serif;
  }
  .success-container h2 {
    font-weight: 700;
    margin-bottom: 20px;
  }
  .success-container .amount {
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 40px;
  }
  .check-orders-btn {
    background-color: #7a9eea;
    border: none;
    padding: 12px 36px;
    color: white;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
  }
  .check-orders-btn:hover {
    background-color: #6078c9;
  }
</style>

<div class="success-container">
  <h2>Pembayaran berhasil</h2>
  <div class="amount">Rp 250.600</div>
  <div style="font-size: 5rem; color: #4caf50; margin-bottom: 40px; text-align: center;">
  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="64" height="64" aria-hidden="true" style="display: inline-block;">
    <path d="M20.285 6.709l-11.37 11.371-5.65-5.649 1.415-1.414 4.235 4.236 9.954-9.954z"/>
  </svg>
</div>
  <a href="{{ url('/customer/orders') }}" class="check-orders-btn" aria-label="Check my orders">Check my orders</a>
</div>

@endsection
