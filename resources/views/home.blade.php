@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="hero-section">
        <div class="hero-content">
            <p>Selamat datang di</p>
            <h1>Posyandu Remaja<br>Cinta Sehat Desa Kuta</h1>
            <em>Dari Remaja Desa Kuta, Untuk Generasi Sehat Indonesia.</em>
        </div>
    </section>

    <section class="posyandu-section">
        <div class="posyandu-content">
            <p>
            <em>
                Posyandu (Pos Pelayanan Terpadu) adalah layanan kesehatan dasar berbasis masyarakat (UKBM) 
                yang dikelola bersama warga desa/kelurahan dengan pendampingan petugas Puskesmas. 
                Tujuannya adalah mempercepat penurunan Angka Kematian Ibu, Bayi, dan Balita 
                melalui partisipasi aktif masyarakat
            </em>
            </p>
        </div>
    </section>

    <style>
        .hero-section {
        background: url('assets/bg1.jpeg') no-repeat center center;
        background-size: cover;
        position: relative;
        height: 500px; /* tinggi penuh layar */
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 50px;
        color: #000;
        }

        /* tambahkan overlay jika mau efek transparan seperti contohmu */
        .hero-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.7); /* putih transparan */
        z-index: 0;
        }

        .hero-content {
        position: relative;
        z-index: 1; /* supaya teks di atas overlay */
        max-width: 600px;
        font-weight: bold;
        }

        .hero-content h1 {
        font-size: 3rem;
        font-weight: 700;
        margin: 10px 0;
        }

        .hero-content p {
        font-size: 1rem;
        margin-bottom: 5px;
        }

        .hero-content em {
        font-size: 1rem;
        font-style: italic;
        }

        .posyandu-section {
        background: url('/assets/bg2.jpeg') no-repeat center center;
        background-size: cover;
        position: relative;
        padding: 50px 20px;
        display: flex;

        }

        /* overlay supaya background tampak lembut seperti contoh */
        .posyandu-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.75); /* putih transparan */
        z-index: 0;
        }

        .posyandu-content {
        position: relative;
        z-index: 1;
        max-width: 1040px;
        text-align: justify;
        padding-left: 50px;
        }

        .posyandu-content p {
        font-size: 1rem;
        color: #000;
        line-height: 1.6;
        }
    </style>

@endsection