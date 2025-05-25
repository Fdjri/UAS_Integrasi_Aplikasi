<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - TixGo</title>

  <style>
    body, html {
      margin: 0; padding: 0; height: 100%;
      font-family: Arial, sans-serif;
      overflow: hidden;
      position: relative;
      color: #333;
    }

    #bg1, #bg2 {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      transition: opacity 1.5s ease-in-out;
      opacity: 0;
      z-index: 0;
    }
    #bg1.active, #bg2.active {
      opacity: 1;
    }

    .background-blur {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      background-color: rgba(255,255,255,0.2);
      opacity: 0;
      transition: opacity 1s ease;
      z-index: 1;
    }
    .background-blur.active {
      opacity: 1;
    }

    .register-container {
      position: relative;
      z-index: 2;
      width: 360px;
      background: #d3d3d3cc;
      border-radius: 20px;
      padding: 30px 25px;
      box-sizing: border-box;
      margin: 80px auto;
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 1s ease, transform 1s ease;
    }
    .register-container.active {
      opacity: 1;
      transform: translateY(0);
    }

    h2 {
      margin: 0 0 20px;
      font-weight: 600;
      color: #222;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
      box-sizing: border-box;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
      outline: none;
      border-color: #0033cc;
      box-shadow: 0 0 5px #0033ccaa;
    }

    button.register-btn {
      width: 100%;
      padding: 12px;
      background-color: #0033cc;
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button.register-btn:hover {
      background-color: #0022aa;
    }

    .disclaimer {
      font-size: 10px;
      color: #666;
      text-align: center;
      line-height: 1.3;
    }
    .disclaimer a {
      color: #0033cc;
      text-decoration: none;
    }
    .disclaimer a:hover {
      text-decoration: underline;
    }

    @media(max-width: 400px) {
      .register-container {
        width: 90%;
        margin: 50px auto;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <div id="bg1"></div>
  <div id="bg2"></div>

  <div class="background-blur" id="bgBlur"></div>

  <div class="register-container" id="registerBox" role="main" aria-label="Form Register">
    <h2>Daftar</h2>
    <form action="{{ route('register') }}" method="POST">
      @csrf
      <input type="text" name="username" placeholder="Username*" required aria-required="true" autocomplete="username" />
      <input type="email" name="email" placeholder="Email*" required aria-required="true" autocomplete="email" />
      <input type="password" name="password" placeholder="Kata Sandi*" required aria-required="true" autocomplete="new-password" />
      <button type="submit" class="register-btn">Daftar</button>
    </form>

    <!-- Tambahan link ke login -->
    <div style="text-align:center; font-size:13px; margin:15px 0;">
      Sudah punya akun? <a href="{{ route('login') }}" style="color:#0033cc; text-decoration:none; font-weight:600;">Login sekarang!</a>
    </div>

    <div class="disclaimer" style="margin-top: 15px;">
      Dengan membuat akun, kamu menyetujui 
      <a href="#">Kebijakan Privasi</a> dan <a href="#">Syarat Ketentuan</a> TixGO<br>
      TixGo All Right Reserved
    </div>
  </div>

  <script>
    const backgrounds = [
      '{{ asset("images/bg1.jpg") }}',
      '{{ asset("images/bg2.jpg") }}',
      '{{ asset("images/bg3.jpg") }}',
      '{{ asset("images/bg4.jpg") }}',
      '{{ asset("images/bg5.jpg") }}',
      '{{ asset("images/bg6.jpg") }}',
      '{{ asset("images/bg7.jpg") }}',
      '{{ asset("images/bg8.jpg") }}',
      '{{ asset("images/bg9.jpg") }}',
      '{{ asset("images/bg10.jpg") }}',
      '{{ asset("images/bg11.jpg") }}',
      '{{ asset("images/bg12.jpg") }}',
    ];

    let current = 0;
    let next = 1;
    const bg1 = document.getElementById('bg1');
    const bg2 = document.getElementById('bg2');

    bg1.style.backgroundImage = `url(${backgrounds[current]})`;
    bg1.classList.add('active');
    bg2.style.backgroundImage = `url(${backgrounds[next]})`;

    function changeBackground() {
      if (bg1.classList.contains('active')) {
        bg2.style.backgroundImage = `url(${backgrounds[next]})`;
        bg2.classList.add('active');
        bg1.classList.remove('active');
      } else {
        bg1.style.backgroundImage = `url(${backgrounds[next]})`;
        bg1.classList.add('active');
        bg2.classList.remove('active');
      }
      current = next;
      next = (next + 1) % backgrounds.length;
    }

    window.addEventListener('load', () => {
      setInterval(changeBackground, 8000);
      document.getElementById('bgBlur').classList.add('active');
      document.getElementById('registerBox').classList.add('active');
    });
  </script>

</body>
</html>
