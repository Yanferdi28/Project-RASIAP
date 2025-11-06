<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daftar Isi Berkas Arsip Aktif</title>
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
        .arsip-aktif-row {
            background-color: #e6f3ff;
        }
        .arsip-unit-row {
            background-color: #ffffff;
        }
        .indent {
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daftar Isi Berkas Arsip Aktif</h1>
        <h2>UNIT PENGOLAH: {{ $unitPengolah }}</h2> 
        <h2>PERIODE: {{ $periode }}</h2> 
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Berkas</th>
                <th>Jumlah Item</th>
                <th>Uraian Informasi</th>
                <th>Tanggal</th>
                <th>Unit Pengolah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $arsipAktifCounter = 1;
                $totalArsipAktif = 0;
                $totalArsipUnit = 0;
            @endphp
            
            @forelse($records as $arsipAktif)
                @php
                    $arsipUnits = $arsipAktif->arsipUnits;
                    $jumlahItem = $arsipUnits->count();
                    $totalArsipAktif++;
                    $totalArsipUnit += $jumlahItem;
                @endphp
                <tr class="arsip-aktif-row">
                    <td style="text-align: center;">{{ $arsipAktifCounter++ }}.</td>
                    <td><strong>{{ $arsipAktif->nama_berkas }}</strong></td>
                    <td style="text-align: center;">{{ $jumlahItem }}</td>
                    <td>{{ $arsipAktif->uraian ?? '' }}</td>
                    <td>{{ $arsipAktif->created_at->format('d-m-Y') }}</td>
                    <td>{{ $arsipAktif->klasifikasi->kode_klasifikasi ?? 'N/A' }}</td>
                </tr>
                
                @foreach($arsipUnits as $index => $arsipUnit)
                <tr class="arsip-unit-row">
                    <td style="text-align: center;" class="indent">{{ $index + 1 }}</td>
                    <td class="indent">{{ $arsipUnit->uraian_informasi }}</td>
                    <td style="text-align: center;">-</td>
                    <td>{{ $arsipUnit->uraian_informasi }}</td>
                    <td>{{ $arsipUnit->tanggal ? $arsipUnit->tanggal->format('d-m-Y') : '' }}</td>
                    <td>{{ $arsipUnit->unitPengolah->nama_unit ?? '' }}</td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right;">TOTAL:</th>
                <th style="text-align: center;">{{ $totalArsipUnit }}</th>
                <th colspan="3" style="text-align: center;">Arsip Aktif: {{ $totalArsipAktif }}, Arsip Unit: {{ $totalArsipUnit }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>