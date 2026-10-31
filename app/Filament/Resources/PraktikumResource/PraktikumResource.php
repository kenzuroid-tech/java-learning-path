<?php

namespace App\Filament\Resources\PraktikumResource;

use App\Filament\Resources\PraktikumResource\Pages\CreatePraktikum;
use App\Filament\Resources\PraktikumResource\Pages\EditPraktikum;
use App\Filament\Resources\PraktikumResource\Pages\ListPraktikum;
use App\Models\Praktikum as PraktikumModel;
use App\Models\Stage;
use BackedEnum;
use Filament\Actions\CreateAction;
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

class PraktikumResource extends Resource
{
    protected static ?string $model = PraktikumModel::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCommandLine;
    protected static ?string $navigationLabel = 'Praktikum';
    protected static \UnitEnum|string|null $navigationGroup = 'Konten Belajar';
    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'Praktikum';
    protected static ?string $pluralModelLabel = 'Praktikum';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Praktikum')->schema([
                Select::make('stage_id')
                    ->label('Stage')
                    ->options(Stage::orderBy('urutan')->get()->mapWithKeys(fn ($s) => [$s->id => "Stage {$s->urutan}: {$s->nama}"]))
                    ->required()
                    ->searchable(),

                TextInput::make('judul')
                    ->label('Judul Praktikum')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('instruksi')
                    ->label('Instruksi / Deskripsi')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull()
                    ->placeholder('Jelaskan apa yang harus dilakukan siswa...'),
            ])->columns(2),

            Section::make('Kode & Output')->schema([
                Textarea::make('kode_awal')
                    ->label('Kode Awal (Starter Code)')
                    ->rows(8)
                    ->columnSpanFull()
                    ->placeholder("public class Main {\n    public static void main(String[] args) {\n        // tulis kode kamu di sini\n    }\n}")
                    ->extraAttributes(['style' => 'font-family: monospace;']),

                Textarea::make('expected_output')
                    ->label('Expected Output (String Match)')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull()
                    ->placeholder("Hello, World!\n")
                    ->helperText('Output siswa akan di-trim() lalu dibandingkan persis dengan ini.')
                    ->extraAttributes(['style' => 'font-family: monospace;']),
            ]),

            Section::make('Hints (Progressive)')->schema([
                Textarea::make('hint_1')
                    ->label('Hint 1 (Paling Mudah)')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('hint_2')
                    ->label('Hint 2 (Sedang)')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('hint_3')
                    ->label('Hint 3 (Paling Detail)')
                    ->rows(2)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stage.urutan')->label('#')->sortable(),
                TextColumn::make('stage.nama')->label('Stage')->sortable(),
                TextColumn::make('judul')->label('Judul')->searchable()->limit(50),
                TextColumn::make('created_at')->label('Dibuat')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('stage_id')
                    ->label('Stage')
                    ->options(fn () => Stage::orderBy('urutan')->get()->mapWithKeys(fn ($s) => [$s->id => "Stage {$s->urutan}: {$s->nama}"])),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPraktikum::route('/'),
            'create' => CreatePraktikum::route('/create'),
            'edit'   => EditPraktikum::route('/{record}/edit'),
        ];
    }
}
