<?php

namespace App\Filament\Resources\BerkasArsips\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use App\Models\KodeKlasifikasi;
use App\Models\Kategori;
use App\Models\Subkategori;
use App\Models\ArsipUnit;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

class BerkasArsipUnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'arsipUnits';

    protected static ?string $title = 'Unit Berkas Terkait';

    protected static ?string $recordTitleAttribute = 'id_berkas';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id_berkas')
            ->modifyQueryUsing(function (Builder $query) {
                // Order by created_at to ensure consistent numbering
                return $query->orderBy('created_at', 'asc');
            })
            ->columns([
                Tables\Columns\TextColumn::make('no_urut')
                    ->label('No. Item')
                    ->getStateUsing(function ($record, $livewire) {
                        // Get the related berkas arsip record
                        $parentRecord = $livewire->getOwnerRecord();

                        // Get all related arsip units ordered by creation date
                        $relatedUnits = $parentRecord->arsipUnits()
                            ->orderBy('created_at', 'asc')
                            ->get();

                        // Find the position of the current record
                        $position = $relatedUnits->search(function ($item) use ($record) {
                            return $item->getKey() === $record->getKey();
                        });

                        return $position !== false ? $position + 1 : null;
                    })
                    ->default('')
                    ->sortable(false),

                Tables\Columns\TextColumn::make('kodeKlasifikasi.kode_klasifikasi')
                    ->label('Kode Klasifikasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kodeKlasifikasi.uraian')
                    ->label('Uraian Klasifikasi')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->kodeKlasifikasi->uraian ?? '')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('indeks')
                    ->label('Indeks')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_nilai')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_satuan')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([

            ])
            ->emptyStateHeading('Tidak ada unit berkas')
            ->emptyStateDescription('Belum ada unit berkas yang terkait dengan berkas arsip ini.')
            ->emptyStateIcon('heroicon-o-archive-box')
            ->headerActions([
                Action::make('tambah_arsip_unit')
                    ->label('Tambah Arsip Unit')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->modalHeading('Tambah Arsip Unit Baru')
                    ->modalWidth('4xl')
                    ->form([
                        Select::make('kode_klasifikasi_id')
                            ->label('Kode Klasifikasi')
                            ->options(function () {
                                return KodeKlasifikasi::orderBy('kode_klasifikasi')
                                    ->get()
                                    ->mapWithKeys(fn ($item) => [$item->id => "{$item->kode_klasifikasi} - {$item->uraian}"]);
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (?string $state, callable $set) {
                                if (blank($state)) {
                                    $set('retensi_aktif', null);
                                    $set('retensi_inaktif', null);
                                    $set('skkaad', null);
                                    return;
                                }
                                $klasifikasi = KodeKlasifikasi::find($state);
                                if ($klasifikasi) {
                                    $set('retensi_aktif', $klasifikasi->retensi_aktif);
                                    $set('retensi_inaktif', $klasifikasi->retensi_inaktif);
                                    $set('skkaad', $klasifikasi->klasifikasi_keamanan);
                                }
                            }),
                        Select::make('kategori_id')
                            ->label('Kategori')
                            ->options(Kategori::pluck('nama_kategori', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('sub_kategori_id', null)),
                        Select::make('sub_kategori_id')
                            ->label('Sub Kategori')
                            ->options(function (callable $get) {
                                $kategoriId = $get('kategori_id');
                                if (!$kategoriId) return [];
                                return Subkategori::where('kategori_id', $kategoriId)->pluck('nama_sub_kategori', 'id');
                            })
                            ->searchable()
                            ->visible(fn (callable $get) => $get('kategori_id') !== null),
                        TextInput::make('indeks')
                            ->label('Indeks')
                            ->required(),
                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->required(),
                        Textarea::make('uraian_informasi')
                            ->label('Uraian Informasi')
                            ->required(),
                        TextInput::make('jumlah_nilai')
                            ->label('Jumlah')
                            ->numeric()
                            ->required(),
                        Select::make('jumlah_satuan')
                            ->label('Satuan')
                            ->options([
                                'Lembar' => 'Lembar',
                                'Jilid' => 'Jilid',
                                'Bundle' => 'Bundle',
                            ])
                            ->default('Lembar')
                            ->required(),
                        Select::make('tingkat_perkembangan')
                            ->label('Tingkat Perkembangan')
                            ->options([
                                'Asli' => 'Asli',
                                'Salinan' => 'Salinan',
                                'Tembusan' => 'Tembusan',
                                'Pertinggal' => 'Pertinggal',
                            ])
                            ->required(),
                        TextInput::make('skkaad')
                            ->label('SKKAAD')
                            ->disabled()
                            ->dehydrated(true),
                        TextInput::make('retensi_aktif')
                            ->label('Retensi Aktif (Tahun)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(true),
                        TextInput::make('retensi_inaktif')
                            ->label('Retensi Inaktif (Tahun)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(true),
                        TextInput::make('ruangan')->label('Ruangan'),
                        TextInput::make('no_filling')->label('No. Filling/Rak/Lemari'),
                        TextInput::make('no_laci')->label('No. Laci'),
                        TextInput::make('no_folder')->label('No. Folder'),
                        TextInput::make('no_box')->label('No. Box'),
                        FileUpload::make('dokumen')
                            ->label('Upload Dokumen')
                            ->directory('arsip-dokumen')
                            ->preserveFilenames()
                            ->visibility('public')
                            ->disk('public')
                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->maxSize(10240)
                            ->downloadable()
                            ->openable(),
                    ])
                    ->action(function (array $data, $livewire): void {
                        $berkasArsip = $livewire->getOwnerRecord();
                        $user = auth()->user();

                        $arsipUnit = new ArsipUnit();
                        $arsipUnit->fill($data);
                        $arsipUnit->berkas_arsip_id = $berkasArsip->nomor_berkas;
                        $arsipUnit->unit_pengolah_arsip_id = $user->unit_pengolah_id;
                        $arsipUnit->status = 'menunggu';
                        $arsipUnit->save();

                        \Filament\Notifications\Notification::make()
                            ->title('Arsip Unit berhasil ditambahkan')
                            ->success()
                            ->send();
                    })
                    ->visible(function () {
                        $user = auth()->user();
                        return $user->can('create', ArsipUnit::class);
                    }),
            ]);
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->label('Lihat')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\TextInput::make('kodeKlasifikasi.kode_klasifikasi')
                        ->label('Kode Klasifikasi')
                        ->default(fn ($get, $record) => $record->kodeKlasifikasi?->kode_klasifikasi ?? 'Tidak ada')
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('indeks')
                        ->label('Indeks')
                        ->default(fn ($get, $record) => $record->indeks)
                        ->disabled(),
                    \Filament\Forms\Components\DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->default(fn ($get, $record) => $record->tanggal)
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('kategori.nama_kategori')
                        ->label('Kategori')
                        ->default(fn ($get, $record) => $record->kategori?->nama_kategori ?? 'Tidak ada')
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('subKategori.nama_sub_kategori')
                        ->label('Sub Kategori')
                        ->default(fn ($get, $record) => $record->subKategori?->nama_sub_kategori ?? 'Tidak ada')
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('jumlah_nilai')
                        ->label('Jumlah')
                        ->default(fn ($get, $record) => $record->jumlah_nilai . ' ' . $record->jumlah_satuan)
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('tingkat_perkembangan')
                        ->label('Tingkat Perkembangan')
                        ->default(fn ($get, $record) => $record->tingkat_perkembangan)
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('skkaad')
                        ->label('SKKAAD')
                        ->default(fn ($get, $record) => $record->skkaad)
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('status')
                        ->label('Status')
                        ->default(fn ($get, $record) => $record->status)
                        ->disabled(),
                    \Filament\Forms\Components\Textarea::make('uraian_informasi')
                        ->label('Uraian Informasi')
                        ->default(fn ($get, $record) => $record->uraian_informasi)
                        ->disabled()
                        ->columnSpanFull(),
                ])
                ->modalHeading(fn ($record) => 'Detail Unit Berkas: ' . ($record->indeks ?? 'N/A'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->visible(function ($record) {
                    /** @var \App\Models\User $user */
                    $user = \Illuminate\Support\Facades\Auth::user();
                    // View ditampilkan untuk user yang memiliki akses view
                    return $user->can('view', $record);
                }),
            Action::make('edit')
                ->label('Edit')
                ->icon('heroicon-o-pencil')
                ->color('warning')
                ->fillForm(fn ($record) => [
                    'kode_klasifikasi_id' => $record->kode_klasifikasi_id,
                    'indeks' => $record->indeks,
                    'tanggal' => $record->tanggal,
                    'uraian_informasi' => $record->uraian_informasi,
                    'jumlah_nilai' => $record->jumlah_nilai,
                    'jumlah_satuan' => $record->jumlah_satuan ? ucfirst(strtolower($record->jumlah_satuan)) : null,
                    'tingkat_perkembangan' => $record->tingkat_perkembangan ? ucfirst(strtolower($record->tingkat_perkembangan)) : null,
                    'retensi_aktif' => $record->retensi_aktif,
                    'retensi_inaktif' => $record->retensi_inaktif,
                    'skkaad' => $record->skkaad,
                    'ruangan' => $record->ruangan,
                    'no_filling' => $record->no_filling,
                    'no_laci' => $record->no_laci,
                    'no_folder' => $record->no_folder,
                    'no_box' => $record->no_box,
                    'kategori_id' => $record->kategori_id,
                    'sub_kategori_id' => $record->sub_kategori_id,
                    'status' => $record->status,
                    'keterangan' => $record->keterangan,
                ])
                ->form([
                    \Filament\Forms\Components\Select::make('kode_klasifikasi_id')
                        ->label('Kode Klasifikasi')
                        ->options(KodeKlasifikasi::all()->mapWithKeys(fn ($item) => [$item->id => "{$item->kode_klasifikasi} - {$item->uraian}"]))
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (?string $state, callable $set) {
                            if (blank($state)) {
                                $set('retensi_aktif', null);
                                $set('retensi_inaktif', null);
                                $set('skkaad', null);
                                return;
                            }
                            $klasifikasi = KodeKlasifikasi::find($state);
                            if ($klasifikasi) {
                                $set('retensi_aktif', $klasifikasi->retensi_aktif);
                                $set('retensi_inaktif', $klasifikasi->retensi_inaktif);
                                $set('skkaad', $klasifikasi->klasifikasi_keamanan);
                            }
                        }),
                    \Filament\Forms\Components\TextInput::make('indeks')
                        ->label('Indeks'),
                    \Filament\Forms\Components\DatePicker::make('tanggal')
                        ->label('Tanggal'),
                    \Filament\Forms\Components\Select::make('kategori_id')
                        ->label('Kategori')
                        ->options(Kategori::all()->pluck('nama_kategori', 'id'))
                        ->searchable(),
                    \Filament\Forms\Components\Select::make('sub_kategori_id')
                        ->label('Sub Kategori')
                        ->options(SubKategori::all()->pluck('nama_sub_kategori', 'id'))
                        ->searchable(),
                    \Filament\Forms\Components\TextInput::make('jumlah_nilai')
                        ->label('Jumlah Nilai')
                        ->numeric(),
                    \Filament\Forms\Components\Select::make('jumlah_satuan')
                        ->label('Satuan')
                        ->options([
                            'Lembar' => 'Lembar',
                            'Jilid' => 'Jilid',
                            'Bundle' => 'Bundle',
                        ]),
                    \Filament\Forms\Components\Select::make('tingkat_perkembangan')
                        ->label('Tingkat Perkembangan')
                        ->options([
                            'Asli' => 'Asli',
                            'Salinan' => 'Salinan',
                            'Tembusan' => 'Tembusan',
                            'Pertinggal' => 'Pertinggal',
                        ]),
                    \Filament\Forms\Components\TextInput::make('retensi_aktif')
                        ->label('Retensi Aktif')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(true),
                    \Filament\Forms\Components\TextInput::make('retensi_inaktif')
                        ->label('Retensi Inaktif')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(true),
                    \Filament\Forms\Components\TextInput::make('skkaad')
                        ->label('SKKAAD')
                        ->disabled()
                        ->dehydrated(true),
                    \Filament\Forms\Components\TextInput::make('ruangan')
                        ->label('Ruangan'),
                    \Filament\Forms\Components\TextInput::make('no_filling')
                        ->label('No. Filling'),
                    \Filament\Forms\Components\TextInput::make('no_laci')
                        ->label('No. Laci'),
                    \Filament\Forms\Components\TextInput::make('no_folder')
                        ->label('No. Folder'),
                    \Filament\Forms\Components\TextInput::make('no_box')
                        ->label('No. Box'),
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'menunggu' => 'Menunggu',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                        ]),
                    \Filament\Forms\Components\TextInput::make('keterangan')
                        ->label('Keterangan'),
                    \Filament\Forms\Components\Textarea::make('uraian_informasi')
                        ->label('Uraian Informasi')
                        ->columnSpanFull(),
                ])
                ->action(function ($record, array $data) {
                    $record->update($data);
                })
                ->visible(function ($record) {
                    /** @var \App\Models\User $user */
                    $user = \Illuminate\Support\Facades\Auth::user();
                    // Edit ditampilkan untuk user yang memiliki akses update
                    return $user->can('update', $record);
                }),
        ];
    }
}