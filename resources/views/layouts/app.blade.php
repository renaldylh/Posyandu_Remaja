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
            padding-top: 70px; /* prevent content from hiding under fixed navbar */
        }
        .dropdown-toggle::after {
        display: none !important;
        }

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

        @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
            text-align: center;
        }
        .footer-map, .footer-address, .footer-social {
            margin-bottom: 20px;
        }
        }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <div class="dropdown">
            <a class="navbar-brand dropdown-toggle" href="#" id="menuDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 18px;">
                <i class="bi bi-list" style="font-size: 34px;"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="menuDropdown">
                <li><a class="dropdown-item" href="{{ route('kunjungan') }}">Kunjungan</a></li>
                <li><a class="dropdown-item" href="{{ route('gizi') }}">Giji</a></li>
                <li><a class="dropdown-item" href="{{ route('anemia') }}">Anemia</a></li>
                <li><a class="dropdown-item" href="{{ route('tekanan-darah') }}">Tekanan Darah</a></li>
                <li><a class="dropdown-item" href="{{ route('gula-darah') }}">Gula Darah</a></li>
            </ul>
            </div>

            <form class="d-flex align-items-center">
            <a class="nav-link active" aria-current="page" href="{{ route('home') }}" style="color: #000; font-weight: bold;">Home</a>
            <a class="nav-link active" aria-current="page" href="{{ route('prediksi') }}" style="color: #000; font-weight: bold;">Prediksi Diabetes</a>
            <a class="nav-link ms-3" href="{{ route('login') }}" style="background-color: rgba(4, 109, 55, 0.65); border-radius: 50px; color: #fff; font-weight: bold; padding: 5px 15px;">Login</a>
            </form>
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
                    width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>

            <!-- Alamat -->
            <div class="footer-address">
                <p><strong>Kuta Lor RT 03/01, Desa Kuta,</strong><br>
                Kec. Belik, Kab. Pemalang<br>
                Jawa Tengah, Indonesia</p>
                <p class="footer-copy">&copy;2025</p>
            </div>

            <!-- Sosial Media -->
            <div class="footer-social">
                <p>Ikuti Kami :</p>
                <div class="social-icons">
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-x"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                    <a href="#"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
            <!-- <p class="mb-1" style="color: #000;">
                &copy; 2024 Posyandu Remaja Cinta Sehat Desa Kuta
            </p> -->
        </div>
    </footer>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    @stack('scripts')

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>
</html>