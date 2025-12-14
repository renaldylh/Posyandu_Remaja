<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <title>@yield('title', 'Posyandu Remaja')</title>
    @stack('styles')

    <style>
        body {
            padding-top: 70px;
            overflow-x: hidden;
        }
        
        /* Navbar Responsive */
        .navbar {
            padding: 10px 20px;
        }
        .navbar-brand {
            font-weight: bold;
            color: #198754 !important;
            font-size: 1rem;
        }
        .nav-link {
            color: #333 !important;
            font-weight: 500;
            padding: 8px 15px !important;
        }
        .nav-link:hover {
            color: #198754 !important;
        }
        .btn-login {
            background-color: rgba(4, 109, 55, 0.65);
            border-radius: 50px;
            color: #fff !important;
            font-weight: bold;
            padding: 8px 20px !important;
        }
        .btn-login:hover {
            background-color: #198754;
        }
        
        /* Mobile Menu */
        .navbar-toggler {
            border: none;
            padding: 5px;
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .dropdown-toggle::after {
            display: none !important;
        }

        /* Footer Responsive */
        .footer {
            background-color: #0E6636;
            color: white;
            padding: 20px;
            font-style: italic;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }
        .footer-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .footer-map {
            flex: 1;
            max-width: 350px;
            margin-right: 20px;
        }
        .footer-map iframe {
            width: 100%;
            height: 150px;
            border: 0;
        }
        .footer-address {
            flex: 1;
            min-width: 250px;
            font-size: 14px;
            line-height: 1.6;
        }
        .footer-copy {
            margin-top: 50px;
            font-style: italic;
        }
        .footer-social {
            flex: 1;
            max-width: 200px;
            padding-top: 0;
            margin-top: 0; 
        }
        .footer-social p {
            margin-bottom: 10px;
        }
        .social-icons a {
            color: white;
            font-size: 24px;
            margin: 0 5px;
            transition: color 0.3s;
        }
        .social-icons a:hover {
            color: #FFD700;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 60px;
            }
            .navbar {
                padding: 8px 15px;
            }
            .navbar-collapse {
                background: #fff;
                padding: 15px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                margin-top: 10px;
            }
            .nav-link {
                padding: 12px 15px !important;
                border-bottom: 1px solid #eee;
            }
            .btn-login {
                display: block;
                text-align: center;
                margin-top: 10px;
            }
            .footer-container {
                flex-direction: column;
                text-align: center;
            }
            .footer-map, .footer-address, .footer-social {
                margin-bottom: 20px;
                max-width: 100%;
                margin-right: 0;
            }
            .footer-copy {
                margin-top: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 0.9rem;
            }
        }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-heart-pulse-fill text-success"></i> Posyandu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('prediksi') }}">Prediksi Diabetes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-login" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <div>
            @yield('content')
        </div>
    </main>

    <!-- footer -->
    <footer class="footer text-white">
        <div class="footer-container">
            <!-- Peta -->
            <div class="footer-map">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.123456789!2d109.4000000!3d-7.0000000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fb1234567890%3A0x123456789abcdef!2sKuta%20Lor%2C%20Kuta%2C%20Belik%2C%20Pemalang!5e0!3m2!1sid!2sid!4v1690000000000" 
                    allowfullscreen="" loading="lazy">
                </iframe>
            </div>

            <!-- Alamat -->
            <div class="footer-address">
                <p><strong>Kuta Lor RT 03/01, Desa Kuta,</strong><br>
                Kec. Belik, Kab. Pemalang<br>
                Jawa Tengah, Indonesia</p>
                <p class="footer-copy">&copy;2025</p>
            </div>

            <!-- Tips Kesehatan -->
            <div class="footer-social">
                <p><strong>Motivasi Kesehatan:</strong></p>
                <p style="font-size: 14px; line-height: 1.4;">
                    "Pencegahan diabetes lebih baik dari pengobatan. <br>
                    Pola hidup sehat hari ini, investasi kesehatan untuk masa depan."<br>
                    <small>— Cek risiko diabetes Anda sekarang!</small>
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    @stack('scripts')
  </body>
</html>