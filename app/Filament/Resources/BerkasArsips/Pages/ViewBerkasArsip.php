<?php

namespace App\Filament\Resources\BerkasArsips\Pages;

use App\Filament\Resources\BerkasArsips\BerkasArsipResource;
use App\Models\ArsipUnit;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewBerkasArsip extends ViewRecord
{
    protected static string $resource = BerkasArsipResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        if (!$this->getRecord()->userCanView()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat berkas ini');
        }
    }

    protected function getHeaderActions(): array
    {
        $berkasArsip = $this->getRecord();

        return [
            Actions\Action::make('tambahArsipUnit')
                ->label('Tambah Arsip Unit')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->modalHeading('Tambah Arsip Unit ke Berkas')
                ->modalDescription('Pilih arsip unit yang sudah ada untuk ditambahkan ke berkas arsip ini.')
                ->modalWidth('lg')
                ->form([
                    Select::make('arsip_unit_ids')
                        ->label('Pilih Arsip Unit')
                        ->options(function () use ($berkasArsip) {
                            // Ambil arsip unit yang belum terhubung ke berkas arsip manapun
                            // atau yang unit pengolahnya sama dengan berkas arsip ini
                            return ArsipUnit::query()
                                ->whereNull('berkas_arsip_id')
                                ->when($berkasArsip->unit_pengolah_id, function ($query) use ($berkasArsip) {
                                    $query->where('unit_pengolah_arsip_id', $berkasArsip->unit_pengolah_id);
                                })
                                ->orderBy('created_at', 'asc')
                                ->get()
                                ->mapWithKeys(fn ($item) => [
                                    $item->id_berkas => "#{$item->id_berkas} - {$item->indeks} ({$item->tanggal?->format('d/m/Y')})"
                                ]);
                        })
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->required()
                        ->helperText('Pilih satu atau lebih arsip unit yang ingin ditambahkan ke berkas ini.'),
                ])
                ->action(function (array $data) use ($berkasArsip) {
                    $count = 0;
                    
                    foreach ($data['arsip_unit_ids'] as $arsipUnitId) {
                        ArsipUnit::where('id_berkas', $arsipUnitId)
                            ->update(['berkas_arsip_id' => $berkasArsip->nomor_berkas]);
                        $count++;
                    }

                    Notification::make()
                        ->title("{$count} Arsip Unit berhasil ditambahkan ke berkas")
                        ->success()
                        ->send();
                })
                ->visible(fn () => auth()->user()->can('update', $berkasArsip)),

            Actions\EditAction::make()
                ->label('Edit')
                ->visible(auth()->user()->can('update', $this->getRecord())),
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->requiresConfirmation()
                ->modalHeading('Hapus Berkas Arsip')
                ->modalDescription('Apakah Anda yakin ingin menghapus berkas ini?')
                ->visible(auth()->user()->can('delete', $this->getRecord())),
        ];
    }
}