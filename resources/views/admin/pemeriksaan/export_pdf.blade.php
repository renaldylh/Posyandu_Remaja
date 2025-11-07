<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pemeriksaan</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 6px; text-align: center; }
        th { background-color: #f0f0f0; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <h2>Rekap Data Pemeriksaan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Periksa</th>
                <th>No Peserta</th>
                <th>Nama Peserta</th>
                <th>Jenis Kelamin</th>
                <th>Umur</th>
                <th>Tinggi Badan (cm)</th>
                <th>Berat Badan (kg)</th>
                <th>Tekanan Darah</th>
                <th>Hemoglobin</th>
                <th>Gula Darah</th>
                <th>Lingkar Lengan (cm)</th>
                <th>NIK</th>
                <th class="text-left">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ optional($row->tanggal_pemeriksaan)->translatedFormat('d F Y H:i') ?? '-' }}</td>
                    <td>{{ optional($row->peserta)->kode ?? '-' }}</td>
                    <td>{{ optional($row->peserta)->nama ?? '-' }}</td>
                    <td>{{ optional($row->peserta)->jenis_kelamin ?? '-' }}</td>
                    <td>{{ optional($row->peserta)->umur ? optional($row->peserta)->umur . ' th' : '-' }}</td>
                    <td>{{ $row->tinggi_badan }}</td>
                    <td>{{ $row->berat_badan }}</td>
                    <td>{{ $row->tekanan_darah ?? '-' }}</td>
                    <td>{{ $row->hemoglobin ?? '-' }}</td>
                    <td>{{ $row->gula_darah ?? '-' }}</td>
                    <td>{{ $row->lingkar_lengan ?? '-' }}</td>
                    <td>{{ optional($row->peserta)->nik ?? '-' }}</td>
                    <td class="text-left">{{ optional($row->peserta)->alamat ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14">Tidak ada data pemeriksaan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
