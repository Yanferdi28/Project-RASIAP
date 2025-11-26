<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ArsipUnit;
use App\Models\KodeKlasifikasi;
use App\Models\UnitPengolah;
use Illuminate\Database\Eloquent\Model;

class ArsipUnitTestSeeder extends Seeder
{
    public function run(): void
    {
        // Disable observers to prevent URL generation issues during seeding
        Model::unsetEventDispatcher();

        // Get all available unit pengolah
        $unitPengolahIds = UnitPengolah::pluck('id')->toArray();

        // Get some kode klasifikasi IDs for variety
        $kodeKlasifikasiIds = KodeKlasifikasi::where('kode_klasifikasi', 'LIKE', 'PR%')
            ->orWhere('kode_klasifikasi', 'LIKE', 'PW%')
            ->pluck('id')
            ->toArray();

        // Create ArsipUnit records with different unit pengolah
        $arsipUnits = [];
        $statuses = ['pending', 'diterima', 'ditolak'];

        for ($i = 1; $i <= 25; $i++) { // Create 25 records
            $unitPengolahId = $unitPengolahIds[array_rand($unitPengolahIds)];
            $kodeKlasifikasiId = $kodeKlasifikasiIds[array_rand($kodeKlasifikasiIds)];

            $arsipUnits[] = [
                'berkas_arsip_id' => null, // Not linking to any berkas arsip yet
                'kode_klasifikasi_id' => $kodeKlasifikasiId,
                'unit_pengolah_arsip_id' => $unitPengolahId,
                'retensi_aktif' => rand(1, 10),
                'retensi_inaktif' => rand(1, 5),
                'indeks' => 'Indeks ' . $i,
                'no_item_arsip' => $i,
                'uraian_informasi' => "Arsip Unit Test No. " . $i . " - " . $this->generateRandomDescription(),
                'tanggal' => now()->subDays(rand(0, 365 * 2)), // Date within last 2 years
                'jumlah_nilai' => rand(1, 100),
                'jumlah_satuan' => rand(1, 50),
                'tingkat_perkembangan' => ['Terkini', 'Lengkap', 'Sementara'][array_rand(['Terkini', 'Lengkap', 'Sementara'])],
                'skkaad' => 'SKKAAD-' . rand(100, 999),
                'ruangan' => 'Ruang ' . chr(65 + ($i % 5)), // A, B, C, D, E
                'no_filling' => 'FL-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'no_laci' => rand(1, 10),
                'no_folder' => 'FL-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'no_box' => 'BX-' . str_pad(intval($i / 5) + 1, 2, '0', STR_PAD_LEFT),
                'dokumen' => 'Dokumen_' . $i . '.pdf',
                'keterangan' => 'Keterangan untuk arsip unit ' . $i,
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now()->subDays(rand(0, 365)), // Created within last year
                'updated_at' => now()->subDays(rand(0, 365)),
            ];
        }

        // Insert ArsipUnit records in chunks
        $chunks = array_chunk($arsipUnits, 50);
        foreach ($chunks as $chunk) {
            DB::table('arsip_units')->insert($chunk);
        }

        $this->command->info('Arsip Unit test data created successfully: ' . count($arsipUnits) . ' records across different unit pengolah');
    }

    private function generateRandomDescription(): string
    {
        $descriptions = [
            'Dokumen laporan tahunan',
            'Surat keputusan penting',
            'Pengumuman resmi',
            'Dokumen evaluasi kegiatan',
            'Laporan keuangan',
            'Dokumen pengawasan',
            'Surat tugas',
            'Berita acara',
            'Dokumen administrasi',
            'Laporan kinerja',
            'Dokumen pertemuan',
            'Hasil penelitian',
            'Dokumen perencanaan',
            'Laporan pelaksanaan',
            'Dokumen keputusan'
        ];

        return $descriptions[array_rand($descriptions)];
    }
}