<?php

namespace App\Filament\Resources\BerkasArsips\Pages;

use App\Filament\Resources\BerkasArsips\BerkasArsipResource;
use Filament\Actions;
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
        return [
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