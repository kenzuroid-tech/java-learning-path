<?php

namespace Database\Seeders;

use App\Models\ExamGroup;
use App\Models\Stage;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    public function run(): void
    {
        $easy   = ExamGroup::where('nama', 'Ujian Easy')->first();
        $medium = ExamGroup::where('nama', 'Ujian Medium')->first();
        $final  = ExamGroup::where('nama', 'Ujian Final')->first();

        $stages = [
            ['urutan' => 1,  'nama' => 'Pengenalan Java',             'exam_group_id' => $easy?->id,   'deskripsi' => 'Dasar-dasar Java: sintaks, struktur program, dan Hello World.'],
            ['urutan' => 2,  'nama' => 'Variabel & Tipe Data',        'exam_group_id' => $easy?->id,   'deskripsi' => 'Mengenal tipe data primitif, deklarasi variabel, dan casting.'],
            ['urutan' => 3,  'nama' => 'Operator',                    'exam_group_id' => $easy?->id,   'deskripsi' => 'Operator aritmatika, perbandingan, logika, dan assignment.'],
            ['urutan' => 4,  'nama' => 'Percabangan',                 'exam_group_id' => $medium?->id, 'deskripsi' => 'if-else, switch-case, dan ternary operator.'],
            ['urutan' => 5,  'nama' => 'Perulangan',                  'exam_group_id' => $medium?->id, 'deskripsi' => 'for, while, do-while, break dan continue.'],
            ['urutan' => 6,  'nama' => 'Array',                       'exam_group_id' => $final?->id,  'deskripsi' => 'Array 1D, 2D, dan operasi dasar array.'],
            ['urutan' => 7,  'nama' => 'Method',                      'exam_group_id' => $final?->id,  'deskripsi' => 'Deklarasi method, parameter, return value, dan overloading.'],
            ['urutan' => 8,  'nama' => 'OOP Dasar',                   'exam_group_id' => $final?->id,  'deskripsi' => 'Class, object, attribute, dan method.'],
            ['urutan' => 9,  'nama' => 'Constructor & Encapsulation', 'exam_group_id' => $final?->id,  'deskripsi' => 'Constructor, getter, setter, dan access modifier.'],
            ['urutan' => 10, 'nama' => 'Inheritance & Polymorphism',  'exam_group_id' => $final?->id,  'deskripsi' => 'Extends, super, override, dan interface.'],
        ];

        foreach ($stages as $stage) {
            Stage::firstOrCreate(['urutan' => $stage['urutan']], $stage);
        }
    }
}
