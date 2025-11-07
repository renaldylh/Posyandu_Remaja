@extends('layouts.app')

@section('title', 'Grafik Tekanan Darah')

@section('content')
<div class="container my-5">
    <h2 class="text-center text-success fw-bold mb-3">Grafik Tekanan Darah</h2>
    <h4 class="text-center text-success mb-5">Posyandu Remaja Desa Kuta</h4>

    <div id="chart_tekanan" style="width: 100%; height: 500px;"></div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Kategori', 'Jumlah'],
            ['Normal', 10],
            ['Hipotensi', 20],
            ['Hipertensi', 20]
        ]);

        var options = {
            legend: { position: 'none' },
            colors: ['#00BFFF'],
            bar: { groupWidth: '50%' },
            vAxis: { minValue: 0 }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_tekanan'));
        chart.draw(data, options);
    }
</script>
@endsection
