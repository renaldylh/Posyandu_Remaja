<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: url('/assets/bg1.jpeg') no-repeat center center fixed;
            background-size: cover;
            position: relative; /* penting supaya ::before bisa diposisikan relatif */
        }

        /* Overlay putih transparan di atas background */
        body::before {
            content: "";
            position: fixed; /* pakai fixed biar menutupi seluruh layar */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6); /* 👉 ubah nilai terakhir (0.0 - 1.0) untuk tingkat transparansi */
            z-index: 0;
        }

        .login-card {
            position: relative; /* supaya berada di atas overlay */
            max-width: 400px;
            margin: 80px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            z-index: 1; /* pastikan card di atas overlay */
        }

        .login-card img {
            width: 80px;
            margin-bottom: 15px;
        }

        .login-card h3 {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .login-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 40px;
        }

        .input-group-text {
            background: transparent;
            border: none;
        }

        .btn-login {
            width: 100%;
            border-radius: 10px;
            background-color: #046D37;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #03582C;
        }
    </style>

    <title>Login</title>
  </head>
  <body>
    <div class="login-card">
        <img src="/assets/logo.jpg" alt="Logo Posyandu">
        <h3>Login</h3>
        <p>Sistem Informasi<br>Posyandu Remaja Desa Kuta</p>

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>
</html>