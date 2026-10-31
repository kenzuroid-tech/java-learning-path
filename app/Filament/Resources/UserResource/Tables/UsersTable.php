<?php

namespace App\Filament\Resources\UserResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('username')->label('Username')->searchable()->badge()->color('gray'),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'superadmin' => 'danger',
                        'siswa'      => 'info',
                        default      => 'gray',
                    }),
                TextColumn::make('xp')->label('XP')->sortable()->badge()->color('warning'),
                TextColumn::make('level')
                    ->label('Level')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => "Lvl {$state}"),
                TextColumn::make('placement_done')
                    ->label('Placement')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state ? '✓ Selesai' : '✗ Belum'),
                TextColumn::make('created_at')->label('Daftar')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Role')
                    ->options(['superadmin' => 'Superadmin', 'siswa' => 'Siswa']),
                SelectFilter::make('placement_done')
                    ->label('Status Placement')
                    ->options([1 => 'Sudah', 0 => 'Belum']),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
