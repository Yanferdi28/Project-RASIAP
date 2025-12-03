
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Daftar Isi Berkas Arsip Aktif</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h2 {
            font-size: 12px;
            margin: 3px 0;
            font-weight: normal;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .berkas-row {
            background-color: #ffffff;
        }
        .item-row {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DAFTAR ISI BERKAS ARSIP AKTIF</h1>
        <h2>UNIT PENGOLAH: {{ $unitPengolah }}</h2>
        <h2>PERIODE: {{ $periode }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>KODE KLASIFIKASI<br>/NOMOR BERKAS</th>
                <th>NAMA BERKAS</th>
                <th>TANGGAL<br>BUAT BERKAS</th>
                <th>NO<br>ITEM<br>ARSIP</th>
                <th>URAIAN INFORMASI</th>
                <th>TANGGAL</th>
                <th>JUMLAH</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $noBerkas = 1;
            @endphp
            @forelse($records as $record)
                @php
                    $arsipUnits = $record->arsipUnits->sortBy('created_at');
                    $totalUnits = $arsipUnits->count();
                    $totalJumlah = $arsipUnits->sum('jumlah_nilai');
                @endphp

                {{-- Row 1: Berkas info only --}}
                <tr class="berkas-row">
                    <td>{{ $noBerkas }}</td>
                    <td>{{ $record->klasifikasi->kode_klasifikasi ?? '-' }}</td>
                    <td>{{ $record->nama_berkas }}</td>
                    <td>{{ $record->created_at ? $record->created_at->format('d/m/Y') : '-' }}</td>
                    <td>-</td>
                    <td>{{ $record->uraian ?? '-' }}</td>
                    <td>{{ $record->created_at ? $record->created_at->format('d-m-Y') : '-' }}</td>
                    <td>{{ $totalUnits }}</td>
                    <td>-</td>
                </tr>

                {{-- Row 2+: Arsip units --}}
                @if($totalUnits > 0)
                    @foreach($arsipUnits as $unitIndex => $unit)
                        @php
                            $noItem = $unitIndex + 1;
                        @endphp
                        <tr class="item-row">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $noItem }}</td>
                            <td>{{ $unit->uraian_informasi ?? '-' }}</td>
                            <td>{{ $unit->tanggal ? $unit->tanggal->format('d-m-Y') : '-' }}</td>
                            <td></td>
                            <td>{{ $unit->tingkat_perkembangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endif
                @php
                    $noBerkas++;
                @endphp
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>