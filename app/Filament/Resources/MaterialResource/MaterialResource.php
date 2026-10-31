<?php

namespace App\Filament\Resources\MaterialResource;

use App\Filament\Resources\MaterialResource\Pages\CreateMaterial;
use App\Filament\Resources\MaterialResource\Pages\EditMaterial;
use App\Filament\Resources\MaterialResource\Pages\ListMaterial;
use App\Models\Material;
use App\Models\Stage;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use BackedEnum;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static ?string $navigationLabel = 'Materi';
    protected static \UnitEnum|string|null $navigationGroup = 'Konten Belajar';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Materi';
    protected static ?string $pluralModelLabel = 'Materi';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Materi')->schema([
                Select::make('stage_id')
                    ->label('Stage')
                    ->options(Stage::orderBy('urutan')->get()->mapWithKeys(fn($s) => [$s->id => "Stage {$s->urutan}: {$s->nama}"]))
                    ->required()
                    ->searchable(),
                TextInput::make('urutan')
                    ->label('Urutan')
                    ->numeric()
                    ->required()
                    ->default(1),
                TextInput::make('judul')
                    ->label('Judul Materi')
                    ->required()
                    ->columnSpanFull(),
            ])->columns(2)->columnSpanFull(),
            Section::make('Konten Materi')->schema([
                FileUpload::make('pdf_import')
                    ->label('Import dari PDF (opsional)')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240) // 10MB
                    ->helperText('Upload file PDF untuk mengekstrak teksnya ke editor di bawah. File tidak disimpan, hanya diambil isinya.')
                    ->columnSpanFull()
                    ->dehydrated(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (! $state) {
                            return;
                        }

                        try {
                            // Handle both TemporaryUploadedFile and string path
                            if ($state instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                $filePath = $state->getRealPath();
                            } elseif (is_string($state)) {
                                $filePath = Storage::disk('local')->path('livewire-tmp/' . $state);
                                if (! file_exists($filePath)) {
                                    $filePath = Storage::disk('local')->path($state);
                                }
                            } else {
                                return;
                            }

                            if (! file_exists($filePath)) {
                                throw new \Exception('File tidak ditemukan.');
                            }

                            $parser = new \Smalot\PdfParser\Parser();
                            $pdf = $parser->parseFile($filePath);
                            $text = $pdf->getText();

                            // Clean up the text
                            $text = preg_replace('/\r\n/', "\n", $text);
                            $text = preg_replace('/[ \t]+/', ' ', $text);
                            $text = preg_replace('/\n{3,}/', "\n\n", $text);
                            $text = trim($text);

                            $set('konten', $text);

                            Notification::make()
                                ->title('PDF berhasil diimpor!')
                                ->body('Teks dari PDF telah dimasukkan ke editor. Silakan edit sesuai kebutuhan.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal mengimpor PDF')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                RichEditor::make('konten')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('materi-images')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ]),
            ])->columnSpanFull(),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('stage_id')
            ->columns([
                TextColumn::make('stage.urutan')->label('#')->sortable(),
                TextColumn::make('stage.nama')->label('Stage')->sortable()->searchable(),
                TextColumn::make('urutan')->label('Urutan'),
                TextColumn::make('judul')->label('Judul')->searchable()->limit(50),
                TextColumn::make('created_at')->label('Dibuat')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('stage_id')
                    ->label('Stage')
                    ->options(Stage::orderBy('urutan')->get()->mapWithKeys(fn($s) => [$s->id => "Stage {$s->urutan}: {$s->nama}"])),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMaterial::route('/'),
            'create' => CreateMaterial::route('/create'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}
