<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Daftar Arsip Unit</title>
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
        <h1>Laporan Daftar Arsip Unit</h1>
        <h2>UNIT PENGOLAH: {{ $unitPengolah }}</h2>
        <h2>PERIODE: {{ $periode }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode<br>Klasifikasi</th>
                <th>Indeks</th>
                <th>Uraian Informasi</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Tingkat<br>Perkembangan</th>
                <th>Unit<br>Pengolah</th>
                <th>Retensi<br>Aktif</th>
                <th>Retensi<br>Inaktif</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $record)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $record->kodeKlasifikasi->kode_klasifikasi ?? 'N/A' }}</td>
                    <td>{{ $record->indeks ?? '' }}</td>
                    <td>{{ $record->uraian_informasi ?? '' }}</td>
                    <td>{{ $record->tanggal ? $record->tanggal->format('d-m-Y') : '' }}</td>
                    <td>{{ $record->jumlah_nilai . ' ' . ($record->jumlah_satuan ?? '') }}</td>
                    <td>{{ $record->tingkat_perkembangan ?? '' }}</td>
                    <td>{{ $record->unitPengolah->nama_unit ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $record->retensi_aktif ?? 0 }}</td>
                    <td style="text-align: center;">{{ $record->retensi_inaktif ?? 0 }}</td>
                    <td>{{ $record->status ?? '' }}</td>
                    <td>{{ $record->keterangan ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>