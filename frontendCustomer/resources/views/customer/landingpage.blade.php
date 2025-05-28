@extends('customer.layouts.landing')

@section('title', 'Landing Page')

@section('content')
<div class="category-tabs" role="tablist" aria-label="Kategori layanan">
    <a role="tab"
       href="{{ url('/customer/landing?service_type=hotel') }}"
       class="{{ $serviceType === 'hotel' ? 'active' : '' }}"
       aria-selected="{{ $serviceType === 'hotel' ? 'true' : 'false' }}">
        Hotel
    </a>
    <a role="tab"
       href="{{ url('/customer/landing?service_type=event') }}"
       class="{{ $serviceType === 'event' ? 'active' : '' }}"
       aria-selected="{{ $serviceType === 'event' ? 'true' : 'false' }}">
        Event
    </a>
    <a role="tab"
       href="{{ url('/customer/landing?service_type=transportasi') }}"
       class="{{ $serviceType === 'transportasi' ? 'active' : '' }}"
       aria-selected="{{ $serviceType === 'transportasi' ? 'true' : 'false' }}">
        Transportasi
    </a>
</div>

<div class="services-list" role="list" aria-live="polite" aria-atomic="true">
    @if(count($services) > 0)
        @foreach($services as $service)
            <article class="service-card" role="listitem" tabindex="0">
                <img src="{{ asset($service['photo_url'] ?? 'images/bg1.jpg') }}" alt="{{ $service['title'] ?? 'Service Image' }}" />
                <div class="overlay-gradient"></div>
                <div class="info">
                    <div class="title">{{ $service['title'] }}</div>
                    <div class="price">Rp {{ number_format($service['price'], 0, ',', '.') }}</div>
                    <div class="address">{{ $service['service_address'] ?? 'Location not specified' }}</div>
                </div>
                <button class="rent-now"
                        aria-label="Pesan {{ $service['title'] }}"
                        onclick="window.location.href='{{ isset($user) ? url('customer/detail/' . $service['service_id']) : route('login') }}'">
                    Book Now
                </button>
            </article>
        @endforeach
    @else
        <article class="service-card" role="listitem" tabindex="0">
            <img src="{{ asset('images/bg1.jpg') }}" alt="No services available" />
            <div class="overlay-gradient"></div>
            <div class="info">
                <div class="title">No services available</div>
                <div class="price">Rp 0</div>
                <div class="address">Location not specified</div>
            </div>
            <button class="rent-now" disabled aria-label="No services available">Book Now</button>
        </article>
    @endif
</div>

<style>
.service-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 3px 10px rgb(0 0 0 / 0.1);
  cursor: pointer;
  display: flex;
  flex-direction: column;

  /* Animasi saat pertama kali muncul */
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.5s ease forwards;

  /* Transisi untuk animasi hover */
  transition: box-shadow 0.3s ease, opacity 0.3s ease, transform 0.3s ease;
}

/* Hover dan fokus: timbul halus dan naik sedikit */
.service-card:hover,
.service-card:focus-within {
  box-shadow: 0 8px 22px rgb(0 0 0 / 0.2);
  opacity: 1;
  transform: translateY(0) scale(1.03);
}

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.category-tabs {
    max-width: 900px;
    margin: 30px auto 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 12px rgb(0 0 0 / 0.15);
    display: flex;
    justify-content: center;
    gap: 16px;
    padding: 8px;
}
.category-tabs a {
    display: inline-block;
    text-decoration: none;
    background: transparent;
    font-weight: 600;
    padding: 10px 24px;
    border-radius: 8px;
    cursor: pointer;
    color: #555;
    transition: all 0.3s ease;
    font-size: 1rem;
    text-align: center;
}
.category-tabs a.active,
.category-tabs a:hover {
    background-color: #dbe0ff;
    color: #2d43b7;
    box-shadow: 0 3px 10px rgb(45 67 183 / 0.3);
}
.services-list {
    max-width: 1100px;
    margin: 0 auto 40px;
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(220px,1fr));
    gap: 20px;
}
.service-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgb(0 0 0 / 0.1);
    cursor: pointer;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.3s;
}
.service-card:hover {
    box-shadow: 0 8px 22px rgb(0 0 0 / 0.2);
}
.service-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 12px 12px 0 0;
}
.info {
    padding: 12px;
    flex-grow: 1;
}
.title {
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 6px;
}
.price {
    font-weight: 700;
    font-size: 1rem;
    color: #b33527;
}
.address {
    font-size: 0.85rem;
    color: #666;
    margin-top: 6px;
}
.rent-now {
    margin-top: 12px;
    background-color: #2780b3;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}
.rent-now:hover {
    background-color: #8c271e;
}
</style>
@endsection
