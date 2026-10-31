<?php

namespace App\Filament\Resources\UserResource\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Akun')->schema([
                TextInput::make('name')->label('Nama Lengkap')->required(),
                TextInput::make('username')->label('Username')->required()->unique(ignoreRecord: true),
                TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true),
                TextInput::make('password')->label('Password')->password()->dehydrateStateUsing(fn ($state) => bcrypt($state))->dehydrated(fn ($state) => filled($state))->required(fn (string $context) => $context === 'create'),
                Select::make('role')->label('Role')->options(['superadmin' => 'Superadmin', 'siswa' => 'Siswa'])->required()->default('siswa'),
            ])->columns(2),
        ]);
    }
}
