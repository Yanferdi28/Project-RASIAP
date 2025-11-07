<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Daftar Isi Arsip</title>
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
        .archive-header {
            background-color: #d3d3d3;
            font-weight: bold;
        }
        .archive-unit {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Daftar Isi Arsip Aktif</h1>
        <h2>UNIT PENGOLAH: {{ $unitPengolah }}</h2> 
        <h2>PERIODE: {{ $periode }}</h2> 
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Klasifikasi /<br>Nomor Berkas</th>
                <th>Nama Berkas</th>
                <th>Jumlah<br>Item</th>
                <th>Unit Pengolah</th>
                <th>Tanggal</th>
                <th>Uraian Informasi</th>
                <th>Jumlah<br>Nilai</th>
                <th>Jumlah<br>Satuan</th>
                <th>No Item<br>Arsip</th>
                <th>Keterangan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $rowCounter = 1;
            @endphp
            @forelse($records as $index => $record)
                @php
                    $arsipUnits = $record->arsipUnits;
                    $totalUnits = $arsipUnits->count();
                @endphp
                
                {{-- First row: Archive header --}}
                <tr class="archive-header">
                    <td style="text-align: center;">{{ $rowCounter++ }}</td>
                    <td>{{ $record->klasifikasi->kode_klasifikasi ?? 'N/A' }} / {{ $record->nomor_berkas }}</td>
                    <td>{{ $record->nama_berkas }}</td>
                    <td style="text-align: center;">{{ $totalUnits }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td style="text-align: center;">-</td>
                    <td style="text-align: center;">-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                
                {{-- Following rows: Related units --}}
                @if($totalUnits > 0)
                    @foreach($arsipUnits as $unitIndex => $unit)
                    <tr class="archive-unit">
                        <td style="text-align: center;">-</td>
                        <td>{{ $unit->kodeKlasifikasi->kode_klasifikasi ?? '-' }} / {{ $unit->no_item_arsip ?? '-' }}</td>
                        <td>{{ $unit->uraian_informasi ?? '-' }}</td>
                        <td>-</td>
                        <td>{{ $unit->unitPengolah->nama_unit ?? 'N/A' }}</td>
                        <td>{{ $unit->tanggal ? $unit->tanggal->format('d-m-Y') : '-' }}</td>
                        <td>{{ $unit->uraian_informasi ?? '-' }}</td>
                        <td style="text-align: center;">{{ $unit->jumlah_nilai ?? '-' }}</td>
                        <td style="text-align: center;">{{ $unit->jumlah_satuan ?? '-' }}</td>
                        <td>{{ $unit->no_item_arsip ?? '-' }}</td>
                        <td>{{ $unit->keterangan ?? '-' }}</td>
                        <td>{{ ucfirst($unit->status) ?? '-' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr class="archive-unit">
                        <td style="text-align: center;">-</td>
                        <td>-</td>
                        <td colspan="9">Tidak ada unit arsip terkait</td>
                        <td>-</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="12" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>