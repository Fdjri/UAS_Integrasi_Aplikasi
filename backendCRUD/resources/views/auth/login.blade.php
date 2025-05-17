<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Sistem Admin & Service Provider</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f5f3ef;
            margin: 0; padding: 0;
            display: flex; justify-content: center; align-items: center;
            height: 100vh;
            color: #3a3a3a;
        }
        .container {
            background: #ffffff;
            padding: 40px 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            width: 360px;
            box-sizing: border-box;
        }
        h2 {
            font-weight: 600;
            margin-bottom: 24px;
            text-align: center;
            color: #4a403a;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #6e5e4e;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #bfb8ad;
            border-radius: 4px;
            font-size: 15px;
            margin-bottom: 18px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #8c7d6b;
        }
        button {
            display: block;
            margin: 20px auto 0;
            width: 100%;
            max-width: 300px;
            background-color: #6f5846;
            color: #fff;
            padding: 12px 0;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #584733;
        }
        .error {
            color: #bf0000;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 16px;
            font-size: 14px;
            color: #7a6e5a;
            text-align: center;
        }
        .footer a {
            color: #6f5846;
            text-decoration: none;
            font-weight: 600;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Management</h2>

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">Login</button>
        </form>

        <div class="footer">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang!</a>
        </div>
    </div>
</body>
</html>
