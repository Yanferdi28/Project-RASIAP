<?php

namespace App\Services;

use App\Models\ArsipUnit;
use App\Models\KodeKlasifikasi;
use App\Models\UnitPengolah;
use App\Models\Kategori;
use App\Models\SubKategori;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class ArsipUnitImportService implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * Parse tanggal dari berbagai format (Excel serial, DD/MM/YYYY, YYYY-MM-DD)
     */
    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Jika berupa angka (Excel serial number)
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        // Jika format DD/MM/YYYY
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
            return $matches[3] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        }

        // Jika format DD-MM-YYYY
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $value, $matches)) {
            return $matches[3] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        }

        // Jika sudah format YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        // Coba parse dengan Carbon
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function model(array $row): ?ArsipUnit
    {
        // Map kode_klasifikasi to kode_klasifikasi_id and get related data
        $kodeKlasifikasiId = null;
        $retensiAktif = null;
        $retensiInaktif = null;
        $skkaad = null;

        if (isset($row['kode_klasifikasi'])) {
            $kodeKlasifikasi = KodeKlasifikasi::where('kode_klasifikasi', $row['kode_klasifikasi'])->first();
            if ($kodeKlasifikasi) {
                $kodeKlasifikasiId = $kodeKlasifikasi->id;
                // Auto-populate from kode klasifikasi
                $retensiAktif = $kodeKlasifikasi->retensi_aktif;
                $retensiInaktif = $kodeKlasifikasi->retensi_inaktif;
                $skkaad = $kodeKlasifikasi->status_akhir; // Assuming status_akhir contains SKKAAD info
            }
        }

        // Map unit_pengolah to unit_pengolah_arsip_id
        $unitPengolahId = null;
        if (isset($row['unit_pengolah'])) {
            $unitPengolah = UnitPengolah::where('nama_unit', $row['unit_pengolah'])->first();
            if ($unitPengolah) {
                $unitPengolahId = $unitPengolah->id;
            }
        }

        // Map kategori to kategori_id
        $kategoriId = null;
        if (isset($row['kategori'])) {
            $kategori = Kategori::where('nama_kategori', $row['kategori'])->first();
            if ($kategori) {
                $kategoriId = $kategori->id;
            }
        }

        // Map sub_kategori to sub_kategori_id
        $subKategoriId = null;
        if (isset($row['sub_kategori'])) {
            $subKategori = SubKategori::where('nama_sub_kategori', $row['sub_kategori'])->first();
            if ($subKategori) {
                $subKategoriId = $subKategori->id;
            }
        }

        return new ArsipUnit([
            'kode_klasifikasi_id' => $kodeKlasifikasiId,
            'indeks' => $row['indeks'] ?? null,
            'uraian_informasi' => $row['uraian_informasi'] ?? null,
            'tanggal' => $this->parseDate($row['tanggal'] ?? null),
            'jumlah_nilai' => $row['jumlah'] ?? $row['jumlah_nilai'] ?? null,
            'jumlah_satuan' => $row['satuan'] ?? $row['jumlah_satuan'] ?? null,
            'tingkat_perkembangan' => $row['tingkat_perkembangan'] ?? null,
            'unit_pengolah_arsip_id' => $unitPengolahId,
            'retensi_aktif' => $row['retensi_aktif'] ?? $retensiAktif, // Use provided value or auto-populated
            'retensi_inaktif' => $row['retensi_inaktif'] ?? $retensiInaktif, // Use provided value or auto-populated
            'skkaad' => $row['skkaad'] ?? $skkaad, // Use provided value or auto-populated
            'ruangan' => $row['ruangan'] ?? null,
            'no_filling' => $row['no_filling_rak_lemari'] ?? $row['no_filling'] ?? $row['no_filling_rak'] ?? null,
            'no_laci' => $row['no_laci'] ?? null,
            'no_folder' => $row['no_folder'] ?? null,
            'no_box' => $row['no_box'] ?? null,
            'keterangan' => $row['keterangan'] ?? null,
            'status' => 'menunggu',
            'kategori_id' => $kategoriId,
            'sub_kategori_id' => $subKategoriId,
        ]);
    }

    public function importFile(UploadedFile $file): array
    {
        $results = $this->toArray($file->getPathname());
        $data = $results[0]; // Get the first worksheet

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                // Validate required fields
                if (empty($row['kode_klasifikasi']) || empty($row['indeks']) || empty($row['uraian_informasi']) || empty($row['tanggal'])) {
                    $errors[] = "Row " . ($index + 2) . " has missing required fields";
                    $errorCount++;
                    continue;
                }

                // Check if related records exist
                $kodeKlasifikasi = KodeKlasifikasi::where('kode_klasifikasi', $row['kode_klasifikasi'])->first();
                if (!$kodeKlasifikasi) {
                    $errors[] = "Row " . ($index + 2) . " has invalid kode_klasifikasi: " . $row['kode_klasifikasi'];
                    $errorCount++;
                    continue;
                }

                if (isset($row['unit_pengolah']) && !empty($row['unit_pengolah'])) {
                    $unitPengolah = UnitPengolah::where('nama_unit', $row['unit_pengolah'])->first();
                    if (!$unitPengolah) {
                        $errors[] = "Row " . ($index + 2) . " has invalid unit_pengolah: " . $row['unit_pengolah'];
                        $errorCount++;
                        continue;
                    }
                }

                if (isset($row['kategori']) && !empty($row['kategori'])) {
                    $kategori = Kategori::where('nama_kategori', $row['kategori'])->first();
                    if (!$kategori) {
                        $errors[] = "Row " . ($index + 2) . " has invalid kategori: " . $row['kategori'];
                        $errorCount++;
                        continue;
                    }
                }

                if (isset($row['sub_kategori']) && !empty($row['sub_kategori'])) {
                    $subKategori = SubKategori::where('nama_sub_kategori', $row['sub_kategori'])->first();
                    if (!$subKategori) {
                        $errors[] = "Row " . ($index + 2) . " has invalid sub_kategori: " . $row['sub_kategori'];
                        $errorCount++;
                        continue;
                    }
                }

                // Create the record with auto-populated values
                $arsipUnit = new ArsipUnit([
                    'kode_klasifikasi_id' => $kodeKlasifikasi->id,
                    'indeks' => $row['indeks'] ?? null,
                    'uraian_informasi' => $row['uraian_informasi'] ?? null,
                    'tanggal' => $this->parseDate($row['tanggal'] ?? null),
                    'jumlah_nilai' => $row['jumlah'] ?? $row['jumlah_nilai'] ?? null,
                    'jumlah_satuan' => $row['satuan'] ?? $row['jumlah_satuan'] ?? null,
                    'tingkat_perkembangan' => $row['tingkat_perkembangan'] ?? null,
                    'unit_pengolah_arsip_id' => null, // Will be set if provided
                    'retensi_aktif' => $row['retensi_aktif'] ?? $kodeKlasifikasi->retensi_aktif, // Auto-populate if not provided
                    'retensi_inaktif' => $row['retensi_inaktif'] ?? $kodeKlasifikasi->retensi_inaktif, // Auto-populate if not provided
                    'skkaad' => $row['skkaad'] ?? $kodeKlasifikasi->status_akhir, // Auto-populate if not provided
                    'ruangan' => $row['ruangan'] ?? null,
                    'no_filling' => $row['no_filling_rak_lemari'] ?? $row['no_filling'] ?? $row['no_filling_rak'] ?? null,
                    'no_laci' => $row['no_laci'] ?? null,
                    'no_folder' => $row['no_folder'] ?? null,
                    'no_box' => $row['no_box'] ?? null,
                    'keterangan' => $row['keterangan'] ?? null,
                    'status' => 'menunggu',
                    'kategori_id' => null, // Will be set if provided
                    'sub_kategori_id' => null, // Will be set if provided
                ]);

                // Set related IDs if provided in the row
                if (isset($row['unit_pengolah']) && !empty($row['unit_pengolah'])) {
                    $unitPengolah = UnitPengolah::where('nama_unit', $row['unit_pengolah'])->first();
                    if ($unitPengolah) {
                        $arsipUnit->unit_pengolah_arsip_id = $unitPengolah->id;
                    }
                }

                if (isset($row['kategori']) && !empty($row['kategori'])) {
                    $kategori = Kategori::where('nama_kategori', $row['kategori'])->first();
                    if ($kategori) {
                        $arsipUnit->kategori_id = $kategori->id;
                    }
                }

                if (isset($row['sub_kategori']) && !empty($row['sub_kategori'])) {
                    $subKategori = SubKategori::where('nama_sub_kategori', $row['sub_kategori'])->first();
                    if ($subKategori) {
                        $arsipUnit->sub_kategori_id = $subKategori->id;
                    }
                }

                // Save the record using create to trigger model events
                $createdArsipUnit = ArsipUnit::create($arsipUnit->toArray());
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . " error: " . $e->getMessage();
                $errorCount++;
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
        ];
    }
}