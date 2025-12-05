<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Template Laporan Daftar Arsip Unit</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Daftar Arsip Unit</h1>
        <h2>UNIT PENGOLAH: {{ $unitPengolah ?? '...................' }}</h2>
        <h2>PERIODE: {{ $periode ?? '...................' }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Kode<br>Klasifikasi</th>
                <th rowspan="2">Indeks</th>
                <th rowspan="2">Uraian Informasi</th>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Jumlah</th>
                <th rowspan="2">Tingkat<br>Perkembangan</th>
                <th rowspan="2">Unit<br>Pengolah</th>
                <th rowspan="2">Retensi<br>Aktif</th>
                <th rowspan="2">Retensi<br>Inaktif</th>
                <th rowspan="2">SKKAAD</th>
                <th colspan="5">Lokasi Fisik</th>
                <th rowspan="2">Keterangan</th>
            </tr>
            <tr>
                <th>Ruang</th>
                <th>No Rak</th>
                <th>No Laci</th>
                <th>No Box</th>
                <th>No Folder</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; height: 30px;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
