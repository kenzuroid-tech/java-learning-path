<?php

namespace App\Filament\Resources\QuestionResource;

use App\Filament\Resources\QuestionResource\Pages\CreateQuestion;
use App\Filament\Resources\QuestionResource\Pages\EditQuestion;
use App\Filament\Resources\QuestionResource\Pages\ListQuestions;
use App\Models\ExamGroup;
use App\Models\Question;
use App\Models\Stage;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;
    protected static ?string $navigationLabel = 'Soal';
    protected static \UnitEnum|string|null $navigationGroup = 'Konten Belajar';
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'Soal';
    protected static ?string $pluralModelLabel = 'Bank Soal';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            // ── Meta Section ──────────────────────────────────────────────
            Section::make('Informasi Soal')->schema([

                Select::make('tipe')
                    ->label('Jenis Soal')
                    ->options([
                        'placement' => 'Placement Test',
                        'kuis'      => 'Kuis (per Stage)',
                        'ujian'     => 'Ujian (Grup)',
                    ])
                    ->required()
                    ->reactive()
                    ->default('placement'),

                Select::make('tipe_soal')
                    ->label('Tipe Soal')
                    ->options([
                        'pilihan_ganda' => '🔤 Pilihan Ganda',
                        'urutan_kode'   => '🔀 Urutan Kode (Drag & Drop)',
                        'tulis_kode'    => '⌨️ Tulis Kode / Output',
                    ])
                    ->required()
                    ->reactive()
                    ->default('pilihan_ganda'),

                Select::make('stage_id')
                    ->label('Stage')
                    ->options(Stage::orderBy('urutan')->get()->mapWithKeys(fn ($s) => [$s->id => "Stage {$s->urutan}: {$s->nama}"]))
                    ->nullable()
                    ->searchable(),

                Select::make('exam_group_id')
                    ->label('Grup Ujian')
                    ->options(ExamGroup::pluck('nama', 'id'))
                    ->nullable()
                    ->searchable(),

                TextInput::make('topik')
                    ->label('Topik / Tag')
                    ->required()
                    ->placeholder('e.g. variabel, for-loop, array-1d')
                    ->helperText('Digunakan untuk analisis kelemahan siswa'),

                Select::make('tingkat_kesulitan')
                    ->label('Tingkat Kesulitan')
                    ->options([
                        'mudah'  => '🟢 Mudah',
                        'sedang' => '🟡 Sedang',
                        'sulit'  => '🔴 Sulit',
                    ])
                    ->required()
                    ->default('sedang'),

            ])->columns(2),

            // ── Pertanyaan ────────────────────────────────────────────────
            Section::make('Pertanyaan')->schema([
                Textarea::make('pertanyaan')
                    ->label('Teks Pertanyaan / Soal')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull()
                    ->placeholder('Tuliskan soal di sini.'),
            ]),

            // ── Pilihan Ganda Section ─────────────────────────────────────
            Section::make('Opsi Jawaban (Pilihan Ganda)')
                ->schema([
                    TextInput::make('opsi_a')->label('Opsi A')->columnSpanFull(),
                    TextInput::make('opsi_b')->label('Opsi B')->columnSpanFull(),
                    TextInput::make('opsi_c')->label('Opsi C')->columnSpanFull(),
                    TextInput::make('opsi_d')->label('Opsi D')->columnSpanFull(),
                    Select::make('jawaban_benar')
                        ->label('Jawaban Benar')
                        ->options(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D']),
                ])
                ->columns(1),

            // ── Urutan Kode Section ───────────────────────────────────────
            Section::make('Potongan Kode (Urutan Kode)')
                ->description('Masukkan potongan kode dalam URUTAN YANG BENAR. Sistem akan mengacaknya untuk siswa.')
                ->schema([
                    Textarea::make('kode_potongan')
                        ->label('Potongan Kode (JSON)')
                        ->rows(6)
                        ->placeholder('["System.out.println(\\"Hello\\");", "System.out.println(\\"World!\\");"]')
                        ->helperText('Format: JSON array berisi string potongan kode, dalam urutan yang benar.')
                        ->columnSpanFull(),
                ]),

            // ── Tulis Kode Section ────────────────────────────────────────
            Section::make('Expected Output (Tulis Kode)')
                ->description('Siswa akan diminta menuliskan output dari kode yang diberikan di pertanyaan.')
                ->schema([
                    Textarea::make('expected_output')
                        ->label('Output yang Diharapkan')
                        ->rows(4)
                        ->placeholder("Hello, World!\n")
                        ->helperText('Jawaban siswa akan di-trim() lalu dibandingkan tepat dengan ini.')
                        ->extraAttributes(['style' => 'font-family: monospace;'])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('tipe')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'placement' => 'warning',
                        'kuis'      => 'info',
                        'ujian'     => 'danger',
                        default     => 'gray',
                    }),
                TextColumn::make('tipe_soal')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pilihan_ganda' => 'success',
                        'urutan_kode'   => 'warning',
                        'tulis_kode'    => 'info',
                        default         => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'urutan_kode'   => 'Urutan Kode',
                        'tulis_kode'    => 'Tulis Kode',
                        default         => $state,
                    }),
                TextColumn::make('pertanyaan')->label('Pertanyaan')->limit(60)->searchable(),
                TextColumn::make('topik')->label('Topik')->badge()->searchable(),
                TextColumn::make('stage.nama')->label('Stage')->sortable(),
                TextColumn::make('tingkat_kesulitan')
                    ->label('Kesulitan')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'mudah'  => 'success',
                        'sedang' => 'warning',
                        'sulit'  => 'danger',
                        default  => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('tipe')
                    ->label('Jenis Soal')
                    ->options([
                        'placement' => 'Placement Test',
                        'kuis'      => 'Kuis',
                        'ujian'     => 'Ujian',
                    ]),
                SelectFilter::make('tipe_soal')
                    ->label('Tipe Soal')
                    ->options([
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'urutan_kode'   => 'Urutan Kode',
                        'tulis_kode'    => 'Tulis Kode',
                    ]),
                SelectFilter::make('stage_id')
                    ->label('Stage')
                    ->options(fn () => Stage::orderBy('urutan')->get()->mapWithKeys(fn ($s) => [$s->id => "Stage {$s->urutan}: {$s->nama}"])),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListQuestions::route('/'),
            'create' => CreateQuestion::route('/create'),
            'edit'   => EditQuestion::route('/{record}/edit'),
        ];
    }
}
