<?php

namespace App\Filament\Resources\ExamGroupResource;

use App\Filament\Resources\ExamGroupResource\Pages\CreateExamGroup;
use App\Filament\Resources\ExamGroupResource\Pages\EditExamGroup;
use App\Filament\Resources\ExamGroupResource\Pages\ListExamGroups;
use App\Models\ExamGroup;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;

class ExamGroupResource extends Resource
{
    protected static ?string $model = ExamGroup::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static ?string $navigationLabel = 'Grup Ujian';
    protected static \UnitEnum|string|null $navigationGroup = 'Konten Belajar';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Grup Ujian';
    protected static ?string $pluralModelLabel = 'Grup Ujian';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Grup Ujian')->schema([
                TextInput::make('nama')
                    ->label('Nama Grup')
                    ->required()
                    ->placeholder('e.g. Ujian Easy'),
                TextInput::make('stage_dari')
                    ->label('Dari Stage')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(10),
                TextInput::make('stage_sampai')
                    ->label('Sampai Stage')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(10),
                TextInput::make('total_soal')
                    ->label('Total Soal')
                    ->numeric()
                    ->required()
                    ->default(30),
                TextInput::make('nilai_minimum')
                    ->label('Nilai Minimum Lulus')
                    ->numeric()
                    ->required()
                    ->default(80)
                    ->suffix('%'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama Grup')->sortable()->searchable(),
                TextColumn::make('stage_dari')->label('Stage Dari'),
                TextColumn::make('stage_sampai')->label('Stage Sampai'),
                TextColumn::make('total_soal')->label('Total Soal'),
                TextColumn::make('nilai_minimum')->label('Min. Lulus')->suffix('%'),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExamGroups::route('/'),
            'create' => CreateExamGroup::route('/create'),
            'edit' => EditExamGroup::route('/{record}/edit'),
        ];
    }
}
