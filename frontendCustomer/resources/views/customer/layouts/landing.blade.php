<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'TixGo')</title>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet" />

  {{-- Google Fonts Poppins --}}
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />

  <style>
    html, body {
      height: 100%;
      margin: 0; padding: 0;
      font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #333;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    body {
      background-color: #f9fafb;
    }
    main.container {
      flex: 1 0 auto; /* Isi konten mengembang dan mengambil ruang */
      max-width: 1200px;
      margin: 30px auto;
      padding: 0 20px;
    }
    footer {
      flex-shrink: 0; /* Footer tidak mengecil */
      max-width: 1100px;
      margin: 0 auto 30px;
      text-align: center;
      font-size: 0.85rem;
      color: #777;
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

  @include('customer.layouts.partials.header')

  <main class="container flex-grow">
    @yield('content')
  </main>

  @include('customer.layouts.partials.footer')

  <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
