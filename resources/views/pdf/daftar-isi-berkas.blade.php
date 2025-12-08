
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Daftar Isi Berkas Arsip Aktif</title>
    <style>
        @page {
            size: legal landscape;
            margin: 12mm 8mm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            margin: 0 auto;
            padding: 3px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            width: 100%;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            width: 100%;
            display: block;
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
            width: 98%;
            border-collapse: collapse;
            font-size: 9px;
            table-layout: fixed;
            margin: 0 auto;
            max-width: none;
        }
        .table-container {
            display: flex;
            justify-content: center;
            width: 100%;
            margin: 0 auto;
            overflow-x: auto;
            text-align: center;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            overflow: hidden;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            line-height: 1.1;
        }
        /* Auto width columns based on content - Optimized for legal landscape */
        .col-no { width: 2.5%; min-width: 20px; }
        .col-kode { width: 5.5%; min-width: 55px; }
        .col-indeks { width: 3.5%; min-width: 35px; }
        .col-nama-berkas { width: 14%; min-width: 90px; }
        .col-tgl-berkas { width: 4.5%; min-width: 42px; }
        .col-no-item { width: 2.5%; min-width: 22px; }
        .col-uraian { width: 16%; min-width: 100px; }
        .col-tanggal { width: 4.5%; min-width: 42px; }
        .col-tingkat { width: 5.5%; min-width: 48px; }
        .col-jumlah { width: 3%; min-width: 28px; }
        .col-retensi-aktif { width: 4%; min-width: 35px; }
        .col-retensi-inaktif { width: 4%; min-width: 35px; }
        .col-skkaad { width: 4%; min-width: 35px; }
        .col-status { width: 4%; min-width: 35px; }
        .col-lokasi-berkas { width: 5.5%; min-width: 45px; }
        .col-ruang { width: 2.8%; min-width: 26px; }
        .col-rak { width: 2.8%; min-width: 26px; }
        .col-laci { width: 2.8%; min-width: 26px; }
        .col-box { width: 2.8%; min-width: 26px; }
        .col-folder { width: 2.8%; min-width: 26px; }
        .col-keterangan { width: 7%; min-width: 55px; }
        
        .berkas-row {
            background-color: #ffffff;
            font-weight: bold;
        }
        .item-row {
            background-color: #ffffff;
        }
        .text-left {
            text-align: left !important;
        }
        
        /* Print specific optimizations */
        @media print {
            @page {
                margin: 10mm;
            }
            
            body {
                width: 100%;
                margin: 0 auto;
                display: block;
                text-align: center;
            }
            
            .header {
                width: 100%;
                text-align: center;
                margin: 0 auto 10px auto;
            }
            
            .table-container {
                width: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0 auto;
            }
            
            table {
                width: 95%;
                font-size: 7px;
                margin: 0 auto;
                border-collapse: collapse;
            }
            
            th, td {
                padding: 1.5px 1px;
                font-size: 6px;
                border: 0.5px solid #000;
            }
            
            th {
                font-size: 5px;
                line-height: 1;
            }
            
            /* A4 specific adjustments */
            .col-no { width: 2%; min-width: 15px; }
            .col-kode { width: 4.5%; min-width: 40px; }
            .col-indeks { width: 3%; min-width: 25px; }
            .col-nama-berkas { width: 12%; min-width: 80px; }
            .col-uraian { width: 14%; min-width: 90px; }
            .col-tgl-berkas { width: 4%; min-width: 35px; }
            .col-no-item { width: 2%; min-width: 18px; }
            .col-tanggal { width: 4%; min-width: 35px; }
            .col-tingkat { width: 5%; min-width: 40px; }
            .col-jumlah { width: 2.5%; min-width: 22px; }
            .col-retensi-aktif { width: 3.5%; min-width: 30px; }
            .col-retensi-inaktif { width: 3.5%; min-width: 30px; }
            .col-skkaad { width: 3.5%; min-width: 28px; }
            .col-status { width: 3.5%; min-width: 28px; }
            .col-lokasi-berkas { width: 5%; min-width: 38px; }
            .col-ruang { width: 2.5%; min-width: 20px; }
            .col-rak { width: 2.5%; min-width: 20px; }
            .col-laci { width: 2.5%; min-width: 20px; }
            .col-box { width: 2.5%; min-width: 20px; }
            .col-folder { width: 2.5%; min-width: 20px; }
            .col-keterangan { width: 6%; min-width: 45px; }
        }
        
        /* Fallback centering for different paper sizes */
        @page :first {
            margin: 10mm;
        }
        
        /* Additional centering support */
        html {
            text-align: center;
        }
        
        * {
            box-sizing: border-box;
        }
        
        /* Ensure table stays centered regardless of paper size */
        .content-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="header">
            <h1>LAPORAN DAFTAR ISI BERKAS ARSIP AKTIF</h1>
            <h2>UNIT PENGOLAH: {{ $unitPengolah }}</h2>
            <h2>PERIODE: {{ $periode }}</h2>
        </div>

        <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="col-no">NO</th>
                    <th rowspan="2" class="col-kode">KODE<br>KLSF</th>
                    <th rowspan="2" class="col-indeks">INDEKS</th>
                    <th rowspan="2" class="col-nama-berkas">NAMA BERKAS</th>
                    <th rowspan="2" class="col-tgl-berkas">TGL BUAT<br>BERKAS</th>
                    <th rowspan="2" class="col-no-item">NO<br>ITEM</th>
                    <th rowspan="2" class="col-uraian">URAIAN INFORMASI</th>
                    <th rowspan="2" class="col-tanggal">TANGGAL</th>
                    <th rowspan="2" class="col-tingkat">TINGKAT<br>PERKMB</th>
                    <th rowspan="2" class="col-jumlah">JML<br>ITEM</th>
                    <th rowspan="2" class="col-retensi-aktif">RET<br>AKTIF</th>
                    <th rowspan="2" class="col-retensi-inaktif">RET<br>INAKTIF</th>
                    <th rowspan="2" class="col-skkaad">SKKAAD</th>
                    <th rowspan="2" class="col-status">STATUS<br>AKHIR</th>
                    <th rowspan="2" class="col-lokasi-berkas">LOKASI<br>BERKAS</th>
                    <th colspan="5" style="text-align: center;">LOKASI ARSIP</th>
                    <th rowspan="2" class="col-keterangan">KET</th>
                </tr>
                <tr>
                    <th class="col-ruang">Ruang</th>
                    <th class="col-rak">Rak</th>
                    <th class="col-laci">Laci</th>
                    <th class="col-box">Box</th>
                    <th class="col-folder">Folder</th>
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
                        <td class="col-no">{{ $noBerkas }}</td>
                        <td class="col-kode">{{ $record->klasifikasi->kode_klasifikasi ?? '-' }}</td>
                        <td class="col-indeks"></td>
                        <td class="col-nama-berkas text-left">{{ $record->nama_berkas }}</td>
                        <td class="col-tgl-berkas">{{ $record->created_at ? $record->created_at->format('d/m/Y') : '-' }}</td>
                        <td class="col-no-item"></td>
                        <td class="col-uraian text-left">{{ $record->uraian ?? '-' }}</td>
                        <td class="col-tanggal"></td>
                        <td class="col-tingkat"></td>
                        <td class="col-jumlah">{{ $totalUnits }}</td>
                        <td class="col-retensi-aktif">{{ $record->retensi_aktif ?? '-' }}</td>
                        <td class="col-retensi-inaktif">{{ $record->retensi_inaktif ?? '-' }}</td>
                        <td class="col-skkaad">{{ $record->klasifikasi->klasifikasi_keamanan ?? '-' }}</td>
                        <td class="col-status">{{ $record->klasifikasi->status_akhir ?? '-' }}</td>
                        <td class="col-lokasi-berkas text-left">{{ $record->lokasi_fisik ?? '-' }}</td>
                        <td class="col-ruang"></td>
                        <td class="col-rak"></td>
                        <td class="col-laci"></td>
                        <td class="col-box"></td>
                        <td class="col-folder"></td>
                        <td class="col-keterangan"></td>
                    </tr>

                    {{-- Row 2+: Arsip units --}}
                    @if($totalUnits > 0)
                        @foreach($arsipUnits as $unitIndex => $unit)
                            @php
                                $noItem = $unitIndex + 1;
                            @endphp
                            <tr class="item-row">
                                <td class="col-no"></td>
                                <td class="col-kode"></td>
                                <td class="col-indeks">{{ $unit->indeks ?? '-' }}</td>
                                <td class="col-nama-berkas"></td>
                                <td class="col-tgl-berkas"></td>
                                <td class="col-no-item">{{ $noItem }}</td>
                                <td class="col-uraian text-left">{{ $unit->uraian_informasi ?? '-' }}</td>
                                <td class="col-tanggal">{{ $unit->tanggal ? $unit->tanggal->format('d-m-Y') : '-' }}</td>
                                <td class="col-tingkat">{{ $unit->tingkat_perkembangan ?? '-' }}</td>
                                <td class="col-jumlah"></td>
                                <td class="col-retensi-aktif"></td>
                                <td class="col-retensi-inaktif"></td>
                                <td class="col-skkaad"></td>
                                <td class="col-status"></td>
                                <td class="col-lokasi-berkas"></td>
                                <td class="col-ruang">{{ $unit->ruangan ?? '-' }}</td>
                                <td class="col-rak">{{ $unit->no_filling ?? '-' }}</td>
                                <td class="col-laci">{{ $unit->no_laci ?? '-' }}</td>
                                <td class="col-box">{{ $unit->no_box ?? '-' }}</td>
                                <td class="col-folder">{{ $unit->no_folder ?? '-' }}</td>
                                <td class="col-keterangan text-left">{{ $unit->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    @endif
                    @php
                        $noBerkas++;
                    @endphp
                @empty
                    <tr>
                        <td colspan="21" style="text-align: center; padding: 15px; font-style: italic;">Tidak ada data untuk periode yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>