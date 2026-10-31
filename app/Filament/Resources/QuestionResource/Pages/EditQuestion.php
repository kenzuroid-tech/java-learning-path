<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource\QuestionResource;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Transform ['code', 'code'] back to [['baris' => 'code'], ...] for Repeater
        if (isset($data['kode_potongan']) && is_array($data['kode_potongan'])) {
            // If already in [['baris'=>...]] format, leave it; if in ['...'] format, transform
            if (! empty($data['kode_potongan']) && ! isset($data['kode_potongan'][0]['baris'])) {
                $data['kode_potongan'] = array_map(
                    fn ($line) => ['baris' => $line],
                    $data['kode_potongan']
                );
            }
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['kode_potongan']) && is_array($data['kode_potongan'])) {
            $data['kode_potongan'] = array_values(array_map(
                fn ($item) => is_array($item) ? ($item['baris'] ?? '') : $item,
                $data['kode_potongan']
            ));
        }
        return $data;
    }
}
