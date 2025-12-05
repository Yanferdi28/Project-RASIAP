<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Template Laporan Daftar Isi Berkas</title>
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
            font-size: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
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
        <h1>Laporan Daftar Isi Berkas Arsip Aktif</h1>
        <h2>UNIT PENGOLAH: {{ $unitPengolah ?? '...................' }}</h2>
        <h2>PERIODE: {{ $periode ?? '...................' }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">KODE KLASIFIKASI<br>/NOMOR BERKAS</th>
                <th rowspan="2">INDEKS</th>
                <th rowspan="2">NAMA BERKAS</th>
                <th rowspan="2">TANGGAL<br>BUAT BERKAS</th>
                <th rowspan="2">NO<br>ITEM<br>ARSIP</th>
                <th rowspan="2">URAIAN INFORMASI</th>
                <th rowspan="2">TANGGAL</th>
                <th rowspan="2">JUMLAH</th>
                <th rowspan="2">LOKASI BERKAS</th>
                <th colspan="5" style="text-align: center;">LOKASI ARSIP</th>
                <th rowspan="2">KETERANGAN</th>
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
            </tr>
        </tbody>
    </table>
</body>
</html>
