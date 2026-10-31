<?php

namespace Database\Seeders;

use App\Models\ExamGroup;
use Illuminate\Database\Seeder;

class ExamGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['nama' => 'Ujian Easy',   'stage_dari' => 1, 'stage_sampai' => 3,  'total_soal' => 30, 'nilai_minimum' => 80],
            ['nama' => 'Ujian Medium', 'stage_dari' => 4, 'stage_sampai' => 5,  'total_soal' => 20, 'nilai_minimum' => 80],
            ['nama' => 'Ujian Final',  'stage_dari' => 6, 'stage_sampai' => 10, 'total_soal' => 50, 'nilai_minimum' => 80],
        ];

        foreach ($groups as $group) {
            ExamGroup::firstOrCreate(['nama' => $group['nama']], $group);
        }
    }
}
