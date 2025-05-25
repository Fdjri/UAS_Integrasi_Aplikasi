<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - TixGo</title>

<style>
  /* Reset dan dasar */
  body, html {
    margin: 0; padding: 0; height: 100%;
    font-family: Arial, sans-serif;
    overflow: hidden;
    position: relative;
    color: #333;
  }

  /* Dua div background untuk fade slideshow */
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

  /* Overlay blur untuk background */
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

  /* Container utama login */
  .login-container {
    position: relative;
    z-index: 2;
    width: 360px;
    background: #f0f0f0cc;
    border-radius: 20px;
    padding: 30px 25px;
    box-sizing: border-box;
    margin: 80px auto;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 1s ease, transform 1s ease;
  }
  .login-container.active {
    opacity: 1;
    transform: translateY(0);
  }

  /* Judul */
  .login-container h2 {
    margin: 0 0 20px;
    font-weight: 600;
    color: #222;
  }

  /* Input */
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
  input[type="email"]:focus,
  input[type="password"]:focus {
    outline: none;
    border-color: #0033cc;
    box-shadow: 0 0 5px #0033ccaa;
  }

  /* Button login */
  button.login-btn {
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
  button.login-btn:hover {
    background-color: #0022aa;
  }

  /* Login cepat */
  .quick-login {
    text-align: center;
    margin-top: 25px;
    margin-bottom: 20px;
    color: #333;
    font-size: 13px;
  }
  .quick-login .icons {
    margin-top: 8px;
  }
  .quick-login .icons button {
    background: #e7e7e7;
    border: none;
    margin: 0 8px;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 700;
    font-size: 14px;
    transition: background-color 0.3s ease;
  }
  .quick-login .icons button:hover {
    background-color: #c3c3c3;
  }

  /* Daftar link */
  .register-link {
    text-align: center;
    font-size: 13px;
    margin-bottom: 25px;
  }
  .register-link a {
    color: #0033cc;
    text-decoration: none;
    font-weight: 600;
  }
  .register-link a:hover {
    text-decoration: underline;
  }

  /* Disclaimer */
  .disclaimer {
    font-size: 10px;
    color: #666;
    text-align: center;
    line-height: 1.3;
  }

  /* Responsive */
  @media(max-width: 400px) {
    .login-container {
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

<div class="login-container" id="loginBox" role="main" aria-label="Form Login">
  <h2>Login</h2>
  <form action="{{ route('login') }}" method="POST">
    @csrf
    <input
      type="email"
      name="email"
      placeholder="Email"
      value="{{ old('email') }}"
      required
      aria-required="true"
      autocomplete="username"
    />
    <input
      type="password"
      name="password"
      placeholder="Password"
      required
      aria-required="true"
      autocomplete="current-password"
    />
    <button type="submit" class="login-btn">Log in</button>
  </form>

  <div class="quick-login">
    <div>Log in lebih cepat dengan</div>
    <div class="icons" aria-label="Login dengan Google dan Facebook">
      <button aria-label="Login dengan Google">G</button>
      <button aria-label="Login dengan Facebook">f</button>
    </div>
  </div>

  <div class="register-link">
    Belum Punya Akun? <a href="{{ route('register') }}">Daftar, yuk!</a>
  </div>

  <div class="disclaimer">
    Dengan membuat akun, kamu menyetujui <a href="#" style="color:#0033cc;">Kebijakan Privasi</a>
    dan <a href="#" style="color:#0033cc;">Syarat Ketentuan</a> TixGO<br>
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
    document.getElementById('loginBox').classList.add('active');
  });
</script>

</body>
</html>
