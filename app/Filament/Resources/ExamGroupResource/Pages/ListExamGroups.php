<?php

namespace App\Filament\Resources\ExamGroupResource\Pages;

use App\Filament\Resources\ExamGroupResource\ExamGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExamGroups extends ListRecords
{
    protected static string $resource = ExamGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}