@extends('layouts.app')

@section('title', 'Grafik Gizi Posyandu')

@section('content')
<div class="container my-5">
    <h2 class="text-center text-success fw-bold mb-3">Grafik Gizi</h2>
    <h4 class="text-center text-success mb-5">Posyandu Remaja Desa Kuta</h4>

    <div id="bar_chart" style="width: 100%; height: 500px;"></div>
</div>

{{-- Google Charts --}}
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Data grafik gizi
        var data = google.visualization.arrayToDataTable([
            ['Kategori', 'Jumlah'],
            ['Sangat Kurus', 15],
            ['Kurus', 0],
            ['Normal', 20],
            ['Gemuk', 5],
            ['Obesitas', 10]
        ]);

        // Opsi tampilan
        var options = {
            legend: { position: 'none' },
            colors: ['#00BFFF'],
            hAxis: {
                title: '',
                minValue: 0
            },
            vAxis: {
                title: '',
                minValue: 0
            },
            bar: { groupWidth: '50%' }
        };

        // Render chart
        var chart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
        chart.draw(data, options);
    }
</script>
@endsection
