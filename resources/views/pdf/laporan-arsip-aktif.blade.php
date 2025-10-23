<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Daftar Berkas Arsip Aktif</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12px;
            margin: 0;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Daftar Berkas Arsip Aktif</h1>
        <h2>UNIT PENGOLAH: {{ $unitPengolah }}</h2> 
        <h2>PERIODE: {{ $periode }}</h2> 
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Klasifikasi /<br>Nomor Berkas</th>
                <th>Nama Berkas</th>
                <th>Tanggal Buat<br>Berkas</th>
                <th>Kurun Waktu</th>
                <th>Jumlah Item</th>
                <th>Retensi<br>Aktif</th>
                <th>Retensi<br>Inaktif</th>
                <th>Status Akhir</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $record)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $record->klasifikasi->kode_klasifikasi ?? 'N/A' }}</td>
                    <td>{{ $record->nama_berkas }}</td>
                    <td>{{ $record->created_at->format('d-m-Y') }}</td>
                    <td>{{ $record->created_at->format('d M Y') }} s/d {{ $record->created_at->format('d M Y') }}</td> <td style="text-align: center;">-</td> <td style="text-align: center;">{{ $record->retensi_aktif }}</td>
                    <td style="text-align: center;">{{ $record->retensi_inaktif }}</td>
                    <td>{{ $record->penyusutan_akhir }}</td>
                    <td>{{ $record->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>