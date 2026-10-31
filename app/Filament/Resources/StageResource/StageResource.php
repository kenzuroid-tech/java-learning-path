<?php

namespace App\Filament\Resources\StageResource;

use App\Filament\Resources\StageResource\Pages\CreateStage;
use App\Filament\Resources\StageResource\Pages\EditStage;
use App\Filament\Resources\StageResource\Pages\ListStages;
use App\Models\ExamGroup;
use App\Models\Stage;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StageResource extends Resource
{
    protected static ?string $model = Stage::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;
    protected static ?string $navigationLabel = 'Stage / Tahap';
    protected static \UnitEnum|string|null $navigationGroup = 'Konten Belajar';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Stage';
    protected static ?string $pluralModelLabel = 'Stage';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Stage')->schema([
                TextInput::make('urutan')
                    ->label('Urutan')
                    ->numeric()
                    ->required()
                    ->minValue(1)->maxValue(10),

                TextInput::make('nama')
                    ->label('Nama Stage')
                    ->required()
                    ->placeholder('e.g. Pengenalan Java'),

                Select::make('exam_group_id')
                    ->label('Grup Ujian')
                    ->options(ExamGroup::pluck('nama', 'id'))
                    ->required()
                    ->searchable(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('urutan')
            ->columns([
                TextColumn::make('urutan')->label('#')->sortable(),
                TextColumn::make('nama')->label('Nama Stage')->sortable()->searchable(),
                TextColumn::make('examGroup.nama')->label('Grup Ujian'),
                TextColumn::make('materials_count')
                    ->label('Materi')
                    ->counts('materials')
                    ->badge(),
                TextColumn::make('questions_count')
                    ->label('Soal Kuis')
                    ->counts('questions')
                    ->badge(),
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
            'index'  => ListStages::route('/'),
            'create' => CreateStage::route('/create'),
            'edit'   => EditStage::route('/{record}/edit'),
        ];
    }
}
