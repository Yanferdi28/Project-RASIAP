<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArsipUnit;
use App\Models\BerkasArsip;
use App\Models\KodeKlasifikasi;
use App\Models\UnitPengolah;
use App\Models\Kategori;
use App\Models\SubKategori;
use Carbon\Carbon;

class ArsipUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitPengolahIds = UnitPengolah::pluck('id')->toArray();
        $klasifikasiList = KodeKlasifikasi::all();
        $berkasArsipIds = BerkasArsip::pluck('nomor_berkas')->toArray();
        $kategoriList = Kategori::with('subKategori')->get();

        if (empty($unitPengolahIds) || $klasifikasiList->isEmpty()) {
            $this->command->warn('Pastikan UnitPengolahSeeder dan KodeKlasifikasiSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        // Enum values
        $statusList = ['menunggu', 'disetujui', 'ditolak'];
        $tingkatPerkembanganList = ['Asli', 'Salinan', 'Tembusan', 'Pertinggal'];
        $jumlahSatuanList = ['Lembar', 'Jilid', 'Bundle'];

        $arsipData = [
            [
                'indeks' => 'SM/001/2024',
                'uraian_informasi' => 'Surat Undangan Rapat Koordinasi Bulanan',
                'keterangan' => 'Undangan rapat koordinasi untuk seluruh kepala bagian',
            ],
            [
                'indeks' => 'SM/002/2024',
                'uraian_informasi' => 'Laporan Realisasi Anggaran Triwulan I',
                'keterangan' => 'Laporan realisasi anggaran periode Januari - Maret 2024',
            ],
            [
                'indeks' => 'SK/001/2024',
                'uraian_informasi' => 'Surat Keputusan Pengangkatan Pejabat',
                'keterangan' => 'SK pengangkatan kepala bagian baru',
            ],
            [
                'indeks' => 'SM/003/2024',
                'uraian_informasi' => 'Proposal Kegiatan Pelatihan SDM',
                'keterangan' => 'Proposal pelatihan pengembangan kompetensi pegawai',
            ],
            [
                'indeks' => 'MoU/001/2024',
                'uraian_informasi' => 'Perjanjian Kerja Sama dengan Instansi Lain',
                'keterangan' => 'MoU kerja sama bidang teknologi informasi',
            ],
            [
                'indeks' => 'SM/004/2024',
                'uraian_informasi' => 'Berita Acara Serah Terima Barang',
                'keterangan' => 'BAST pengadaan komputer dan perangkat IT',
            ],
            [
                'indeks' => 'LK/001/2024',
                'uraian_informasi' => 'Laporan Kinerja Semester I 2024',
                'keterangan' => 'Laporan capaian kinerja semester pertama',
            ],
            [
                'indeks' => 'SM/005/2024',
                'uraian_informasi' => 'Nota Dinas Permohonan Cuti',
                'keterangan' => 'Permohonan cuti tahunan pegawai',
            ],
            [
                'indeks' => 'SP/001/2024',
                'uraian_informasi' => 'Surat Perintah Perjalanan Dinas',
                'keterangan' => 'SPPD untuk kegiatan monitoring ke daerah',
            ],
            [
                'indeks' => 'SM/006/2024',
                'uraian_informasi' => 'Hasil Evaluasi Kinerja Pegawai',
                'keterangan' => 'Penilaian SKP pegawai tahun 2024',
            ],
            [
                'indeks' => 'SM/007/2024',
                'uraian_informasi' => 'Laporan Hasil Audit Internal',
                'keterangan' => 'Hasil pemeriksaan internal bidang keuangan',
            ],
            [
                'indeks' => 'SK/002/2024',
                'uraian_informasi' => 'Surat Keputusan Pembentukan Tim',
                'keterangan' => 'SK pembentukan tim kerja khusus',
            ],
            [
                'indeks' => 'SM/008/2024',
                'uraian_informasi' => 'Rencana Kerja Anggaran Tahun 2025',
                'keterangan' => 'Draft RKA untuk tahun anggaran berikutnya',
            ],
            [
                'indeks' => 'SM/009/2024',
                'uraian_informasi' => 'Notulen Rapat Pimpinan',
                'keterangan' => 'Hasil rapat pimpinan bulanan',
            ],
            [
                'indeks' => 'SM/010/2024',
                'uraian_informasi' => 'Laporan Inventaris Barang',
                'keterangan' => 'Daftar inventaris BMN terbaru',
            ],
            [
                'indeks' => 'SM/011/2024',
                'uraian_informasi' => 'Surat Edaran Kebijakan Baru',
                'keterangan' => 'Edaran tentang kebijakan kerja hybrid',
            ],
            [
                'indeks' => 'SM/012/2024',
                'uraian_informasi' => 'Dokumen Tender Pengadaan',
                'keterangan' => 'Dokumen lelang pengadaan peralatan kantor',
            ],
            [
                'indeks' => 'LK/002/2024',
                'uraian_informasi' => 'Laporan Keuangan Bulanan',
                'keterangan' => 'Laporan keuangan bulan September 2024',
            ],
            [
                'indeks' => 'SM/013/2024',
                'uraian_informasi' => 'Surat Tugas Pegawai',
                'keterangan' => 'Penugasan pegawai untuk kegiatan khusus',
            ],
            [
                'indeks' => 'SM/014/2024',
                'uraian_informasi' => 'Laporan Monitoring Program',
                'keterangan' => 'Hasil monitoring pelaksanaan program kerja',
            ],
        ];

        foreach ($arsipData as $index => $arsip) {
            $klasifikasi = $klasifikasiList->random();
            $unitPengolahId = $unitPengolahIds[array_rand($unitPengolahIds)];
            
            // Random kategori dan sub kategori
            $kategori = $kategoriList->random();
            $subKategori = $kategori->subKategori->isNotEmpty() 
                ? $kategori->subKategori->random() 
                : null;

            // Random status dengan bobot (lebih banyak disetujui)
            $statusWeight = rand(1, 10);
            if ($statusWeight <= 2) {
                $status = 'ditolak';
            } elseif ($statusWeight <= 5) {
                $status = 'menunggu';
            } else {
                $status = 'disetujui';
            }

            // Random tanggal dalam 1 tahun terakhir
            $tanggal = Carbon::now()->subDays(rand(1, 365));

            ArsipUnit::create([
                'berkas_arsip_id' => !empty($berkasArsipIds) ? $berkasArsipIds[array_rand($berkasArsipIds)] : null,
                'kode_klasifikasi_id' => $klasifikasi->id,
                'unit_pengolah_arsip_id' => $unitPengolahId,
                'retensi_aktif' => $klasifikasi->retensi_aktif,
                'retensi_inaktif' => $klasifikasi->retensi_inaktif,
                'indeks' => $arsip['indeks'],
                'no_item_arsip' => 'ITEM-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'uraian_informasi' => $arsip['uraian_informasi'],
                'tanggal' => $tanggal,
                'jumlah_nilai' => rand(1, 50),
                'jumlah_satuan' => $jumlahSatuanList[array_rand($jumlahSatuanList)],
                'tingkat_perkembangan' => $tingkatPerkembanganList[array_rand($tingkatPerkembanganList)],
                'skkaad' => $klasifikasi->klasifikasi_keamanan,
                'ruangan' => 'R-' . rand(1, 10),
                'no_filling' => 'F-' . rand(1, 20),
                'no_laci' => 'L-' . rand(1, 5),
                'no_folder' => 'FD-' . rand(1, 100),
                'no_box' => 'BX-' . rand(1, 50),
                'keterangan' => $arsip['keterangan'],
                'status' => $status,
                'kategori_id' => $kategori->id,
                'sub_kategori_id' => $subKategori?->id,
                'verifikasi_keterangan' => $status !== 'menunggu' ? 'Verifikasi oleh sistem seeder' : null,
                'verifikasi_tanggal' => $status !== 'menunggu' ? now() : null,
            ]);
        }

        $this->command->info('ArsipUnitSeeder: ' . count($arsipData) . ' arsip unit berhasil dibuat.');
    }
}
