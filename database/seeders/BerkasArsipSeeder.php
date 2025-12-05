<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BerkasArsip;
use App\Models\KodeKlasifikasi;

class BerkasArsipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $klasifikasiIds = KodeKlasifikasi::pluck('id')->toArray();

        if (empty($klasifikasiIds)) {
            $this->command->warn('Pastikan KodeKlasifikasiSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        $penyusutanAkhir = ['Permanen', 'Musnah'];

        $berkasList = [
            [
                'nama_berkas' => 'Berkas Surat Masuk 2024',
                'uraian' => 'Kumpulan surat masuk tahun 2024',
                'lokasi_fisik' => 'Rak A1 - Ruang Arsip Lantai 1',
            ],
            [
                'nama_berkas' => 'Berkas Surat Keluar 2024',
                'uraian' => 'Kumpulan surat keluar tahun 2024',
                'lokasi_fisik' => 'Rak A2 - Ruang Arsip Lantai 1',
            ],
            [
                'nama_berkas' => 'Berkas Laporan Keuangan',
                'uraian' => 'Dokumen laporan keuangan tahunan',
                'lokasi_fisik' => 'Rak B1 - Ruang Arsip Lantai 2',
            ],
            [
                'nama_berkas' => 'Berkas Kepegawaian',
                'uraian' => 'Dokumen administrasi kepegawaian',
                'lokasi_fisik' => 'Rak C1 - Ruang SDM',
            ],
            [
                'nama_berkas' => 'Berkas Pengadaan Barang',
                'uraian' => 'Dokumen pengadaan barang dan jasa',
                'lokasi_fisik' => 'Rak D1 - Ruang Arsip Lantai 1',
            ],
            [
                'nama_berkas' => 'Berkas Rapat Pimpinan',
                'uraian' => 'Notulen dan dokumen rapat pimpinan',
                'lokasi_fisik' => 'Rak E1 - Ruang Sekretariat',
            ],
            [
                'nama_berkas' => 'Berkas Kerja Sama',
                'uraian' => 'Dokumen MoU dan perjanjian kerja sama',
                'lokasi_fisik' => 'Rak F1 - Ruang Hukum',
            ],
            [
                'nama_berkas' => 'Berkas Inventaris BMN',
                'uraian' => 'Dokumen inventaris barang milik negara',
                'lokasi_fisik' => 'Rak G1 - Ruang Perlengkapan',
            ],
            [
                'nama_berkas' => 'Berkas Pelatihan SDM',
                'uraian' => 'Dokumen pelatihan dan pengembangan SDM',
                'lokasi_fisik' => 'Rak C2 - Ruang SDM',
            ],
            [
                'nama_berkas' => 'Berkas Program Siaran',
                'uraian' => 'Dokumen program dan jadwal siaran',
                'lokasi_fisik' => 'Rak H1 - Ruang Siaran',
            ],
        ];

        foreach ($berkasList as $index => $berkas) {
            $klasifikasi = KodeKlasifikasi::find($klasifikasiIds[array_rand($klasifikasiIds)]);
            
            BerkasArsip::create([
                'nama_berkas' => $berkas['nama_berkas'],
                'klasifikasi_id' => $klasifikasi->id,
                'retensi_aktif' => $klasifikasi->retensi_aktif,
                'retensi_inaktif' => $klasifikasi->retensi_inaktif,
                'penyusutan_akhir' => $penyusutanAkhir[array_rand($penyusutanAkhir)],
                'lokasi_fisik' => $berkas['lokasi_fisik'],
                'uraian' => $berkas['uraian'],
            ]);
        }

        $this->command->info('BerkasArsipSeeder: ' . count($berkasList) . ' berkas arsip berhasil dibuat.');
    }
}
