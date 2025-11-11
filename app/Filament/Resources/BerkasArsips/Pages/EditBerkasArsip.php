<?php

namespace App\Filament\Resources\BerkasArsips\Pages;

use App\Filament\Resources\BerkasArsips\BerkasArsipResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBerkasArsip extends EditRecord
{
    protected static string $resource = BerkasArsipResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        if (!$this->getRecord()->userCanUpdate()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit berkas ini');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus')
                ->requiresConfirmation()
                ->modalHeading('Hapus Berkas Arsip')
                ->modalDescription('Apakah Anda yakin ingin menghapus berkas ini?')
                ->visible(auth()->user()->can('delete', $this->getRecord())),
        ];
    }
}