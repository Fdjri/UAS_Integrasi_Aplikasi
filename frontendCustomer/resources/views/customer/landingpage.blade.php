@extends('customer.layouts.landing')

@section('title', 'Landing Page')

@section('content')
    <div class="category-tabs" role="tablist" aria-label="Kategori layanan">
        <button class="active" aria-selected="true" role="tab">Hotel</button>
        <button role="tab">Event</button>
        <button role="tab">Transportasi</button>
    </div>

    <div class="services-list" role="list">
        @foreach ($services as $service)
            <article class="service-card" role="listitem" tabindex="0">
                <img src="{{ $service['image_url'] ?? asset('images/hotel-placeholder.jpg') }}" alt="{{ $service['title'] }}" />
                <div class="overlay-gradient"></div>
                <div class="info">
                    <div class="title">{{ $service['title'] }}</div>
                    <div class="price">Rp. {{ number_format($service['price'], 0, ',', '.') }}</div>
                </div>
                <button class="rent-now" aria-label="Pesan {{ $service['title'] }}">Rent Now</button>
                <div class="favorite-icon" role="button" tabindex="0" aria-label="Tambahkan ke favorit">&#9825;</div>
            </article>
        @endforeach
    </div>

    <style>
        /* Kategori */
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
        .category-tabs button {
            background: transparent;
            border: none;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            color: #555;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        .category-tabs button.active,
        .category-tabs button:hover {
            background-color: #dbe0ff;
            color: #2d43b7;
            box-shadow: 0 3px 10px rgb(45 67 183 / 0.3);
        }

        /* Daftar layanan */
        .services-list {
            max-width: 1100px;
            margin: 0 auto 40px;
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 8px;
        }
        .services-list::-webkit-scrollbar {
            height: 8px;
        }
        .services-list::-webkit-scrollbar-thumb {
            background-color: #2d43b7;
            border-radius: 4px;
        }

        /* Kartu layanan */
        .service-card {
            position: relative;
            min-width: 180px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgb(0 0 0 / 0.1);
            transition: box-shadow 0.3s;
            outline: none;
        }
        .service-card:focus {
            box-shadow: 0 0 0 3px #2d43b7;
        }
        .service-card:hover {
            box-shadow: 0 8px 22px rgb(0 0 0 / 0.2);
        }

        .service-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            border-radius: 12px;
        }

        .overlay-gradient {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.7) 100%);
            border-radius: 0 0 12px 12px;
            pointer-events: none;
        }

        .info {
            position: absolute;
            bottom: 8px;
            left: 12px;
            color: white;
            z-index: 10;
            font-size: 0.85rem;
        }
        .title {
            font-weight: 600;
            margin-bottom: 4px;
        }
        .price {
            font-weight: 700;
            font-size: 0.9rem;
        }

        .rent-now {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: white;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .rent-now:hover {
            background-color: #e0e0e0;
        }

        .favorite-icon {
            position: absolute;
            top: 8px;
            right: 8px;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            text-shadow: 0 0 6px rgba(0,0,0,0.6);
            transition: color 0.3s;
        }
        .favorite-icon:hover {
            color: #e00;
        }
    </style>
@endsection
