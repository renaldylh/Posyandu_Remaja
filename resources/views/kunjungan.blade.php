@extends('layouts.app')

@section('title', 'Grafik Kunjungan Posyandu')

@section('content')
<div class="container my-5">
    <h2 class="text-center text-success fw-bold mb-3">Grafik Kunjungan</h2>
    <h4 class="text-center text-success mb-5">Posyandu Remaja Desa Kuta</h4>

    <div id="chart_div" style="width: 100%; height: 500px;"></div>
</div>

{{-- Load Google Charts --}}
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Data grafik
        var data = google.visualization.arrayToDataTable([
            ['Bulan', 'Kelompok A', 'Kelompok B', 'Kelompok C'],
            ['Januari',   0, 0, 0],
            ['Februari',  1, 0, 0],
            ['Maret',     0, 1, 0],
            ['April',     0, 0, 1],
            ['Mei',       1, 1, 0],
            ['Juni',      1, 0, 1],
            ['Juli',      0, 1, 0],
            ['Agustus',   0, 0, 1],
            ['September', 1, 0, 0],
            ['Oktober',   0, 1, 0],
            ['November',  0, 0, 1],
            ['Desember',  0, 0, 0]
        ]);

        // Opsi tampilan
        var options = {
            title: '',
            curveType: 'function',
            legend: { position: 'bottom' },
            colors: ['#006400', '#00BFFF', '#FFD700'],
            hAxis: {
                title: '',
                slantedText: true,
                slantedTextAngle: 45
            },
            vAxis: {
                minValue: 0,
                maxValue: 1
            }
        };

        // Tampilkan grafik
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>
@endsection
