<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource\QuestionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Transform Repeater kode_potongan from [['baris'=>'...'], ...] to ['...', '...']
        if (isset($data['kode_potongan']) && is_array($data['kode_potongan'])) {
            $data['kode_potongan'] = array_values(array_map(
                fn ($item) => is_array($item) ? ($item['baris'] ?? '') : $item,
                $data['kode_potongan']
            ));
        }
        return $data;
    }
}
