@extends('customer.layouts.landing')

@section('title', $service['title'] ?? 'Detail Service')

@section('content')
<!-- Tombol kembali di luar wrapper -->
<div class="back-wrapper">
    <button class="btn-back" onclick="window.history.back()">
        &larr; Kembali
    </button>
</div>

<div class="detail-container">
    <div class="detail-sidebar">
        <div class="detail-image">
            <img src="{{ asset($service['photo_url'] ?? 'images/bg1.jpg') }}" alt="{{ $service['title'] ?? 'Service Image' }}" />
        </div>
    </div>

    <div class="detail-info">
        <h2 class="title">{{ $service['title'] ?? 'Service Title' }}</h2>
        <p class="address"><strong>Address</strong><br>{{ $service['service_address'] ?? '-' }}</p>
        <p class="description"><strong>Desc</strong><br>{{ $service['description'] ?? '-' }}</p>

        <div class="footer-info">
            <div class="price">Rp {{ number_format($service['price'] ?? 0, 0, ',', '.') }}</div>
            <button class="btn-book-now"
                onclick="window.location.href='{{ route('customer.booking') }}'">
                Book Now
            </button>
        </div>
    </div>
</div>

<style>
.back-wrapper {
    max-width: 1100px;
    margin: 30px auto 10px;
    padding: 0 20px;
    display: flex;
}
.btn-back {
    background-color: transparent;
    border: 1.5px solid #888;
    padding: 6px 16px;
    font-size: 0.95rem;
    border-radius: 6px;
    cursor: pointer;
    color: #333;
    transition: background-color 0.3s ease, color 0.3s ease;
}
.btn-back:hover {
    background-color: #e0e0e0;
}

.detail-container {
    max-width: 1100px;
    margin: 0 auto 40px;
    padding: 30px;
    background-color: #e2e2e2;
    border-radius: 12px;
    display: flex;
    gap: 30px;
    align-items: flex-start;
}
.detail-sidebar {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.detail-image img {
    width: 280px;
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.detail-info {
    flex: 1;
}
.title {
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 20px;
}
.address, .description {
    font-size: 1.1rem;
    margin-bottom: 20px;
    color: #333;
}
.footer-info {
    margin-top: 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.price {
    font-weight: 700;
    font-size: 1.6rem;
    color: #b33527;
}
.btn-book-now {
    background-color: #7aa9f7;
    border: none;
    padding: 12px 28px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.btn-book-now:hover {
    background-color: #5a88e6;
}

@media (max-width: 768px) {
    .detail-container {
        flex-direction: column;
        padding: 20px;
        text-align: center;
    }
    .detail-sidebar {
        align-items: center;
    }
    .detail-image img {
        width: 100%;
        max-width: 400px;
    }
    .footer-info {
        flex-direction: column;
        gap: 16px;
        margin-top: 30px;
    }
    .btn-back {
        align-self: center;
    }
}
</style>
@endsection
