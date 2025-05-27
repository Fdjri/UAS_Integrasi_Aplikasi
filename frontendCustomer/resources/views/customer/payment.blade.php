@extends('customer.layouts.landing')

@section('title', 'Pembayaran')

@section('content')
<style>
  .payment-container {
    max-width: 900px;
    margin: 40px auto;
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    font-family: 'Poppins', sans-serif;
  }
  .payment-method {
    background-color: #ccc;
    flex: 1 1 350px;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.5rem;
    border-radius: 12px;
  }
  .detail-info {
    background-color: #ccc;
    flex: 1 1 500px;
    display: flex;
    gap: 16px;
    border-radius: 12px;
    padding: 12px;
  }
  .detail-info img {
    width: 160px;
    height: 120px;
    object-fit: cover;
    border-radius: 12px;
  }
  .detail-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .detail-text h3 {
    margin: 0 0 12px 0;
    font-weight: 700;
  }
  .detail-text p {
    margin: 0;
  }
  .footer-payment {
    margin-top: 40px;
    background-color: #ccc;
    border-radius: 12px;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .price {
    font-weight: 700;
    font-size: 1.5rem;
  }
  .btn-pay {
    background-color: #7a9eea;
    border: none;
    padding: 10px 30px;
    color: white;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
  }
  .btn-pay:hover {
    background-color: #6078c9;
  }
</style>

<div class="payment-container">
  <div class="payment-method">
    Metode Pembayaran
  </div>

  <div class="detail-info">
    <img src="{{ asset('images/bg1.jpg') }}" alt="Monoloog Hotel Bekasi" />
    <div class="detail-text">
      <h3>Monoloog Hotel Bekasi</h3>
      <p>Address</p>
    </div>
  </div>

  <div class="footer-payment" style="width: 100%;">
        <div class="price">Rp 250.600</div>
        <button 
            class="btn-pay" 
            aria-label="Bayar Sekarang"
            onclick="window.location.href='{{ url('customer/success') }}'"
        >
            Pay
        </button>
    </div>
</div>
@endsection
