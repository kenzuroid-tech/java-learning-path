<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Stage;
use Illuminate\Database\Seeder;

class PlacementQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $stage1 = Stage::where('urutan', 1)->first();
        $stage2 = Stage::where('urutan', 2)->first();
        $stage3 = Stage::where('urutan', 3)->first();
        $stage4 = Stage::where('urutan', 4)->first();
        $stage5 = Stage::where('urutan', 5)->first();

        $questions = [

            // ─── PILIHAN GANDA ────────────────────────────────────────────

            [
                'stage_id'       => $stage1?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'pilihan_ganda',
                'pertanyaan'     => 'Apa ekstensi file program Java?',
                'opsi_a'         => '.java',
                'opsi_b'         => '.jv',
                'opsi_c'         => '.class',
                'opsi_d'         => '.jar',
                'jawaban_benar'  => 'a',
                'topik'          => 'pengenalan-java',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'stage_id'       => $stage1?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'pilihan_ganda',
                'pertanyaan'     => 'Method utama yang dijalankan pertama kali di Java adalah?',
                'opsi_a'         => 'start()',
                'opsi_b'         => 'run()',
                'opsi_c'         => 'main()',
                'opsi_d'         => 'init()',
                'jawaban_benar'  => 'c',
                'topik'          => 'pengenalan-java',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'stage_id'       => $stage2?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'pilihan_ganda',
                'pertanyaan'     => 'Tipe data yang digunakan untuk menyimpan bilangan bulat di Java adalah?',
                'opsi_a'         => 'double',
                'opsi_b'         => 'float',
                'opsi_c'         => 'int',
                'opsi_d'         => 'char',
                'jawaban_benar'  => 'c',
                'topik'          => 'tipe-data',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'stage_id'       => $stage2?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'pilihan_ganda',
                'pertanyaan'     => 'Deklarasi variabel String yang benar di Java adalah?',
                'opsi_a'         => 'string nama = "Budi";',
                'opsi_b'         => 'String nama = "Budi";',
                'opsi_c'         => 'var nama : String = "Budi";',
                'opsi_d'         => 'nama = "Budi" : String;',
                'jawaban_benar'  => 'b',
                'topik'          => 'variabel',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'stage_id'       => $stage3?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'pilihan_ganda',
                'pertanyaan'     => 'Hasil dari operasi: 10 % 3 di Java adalah?',
                'opsi_a'         => '3',
                'opsi_b'         => '1',
                'opsi_c'         => '0',
                'opsi_d'         => '2',
                'jawaban_benar'  => 'b',
                'topik'          => 'operator',
                'tingkat_kesulitan' => 'sedang',
            ],
            [
                'stage_id'       => $stage4?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'pilihan_ganda',
                'pertanyaan'     => 'Pernyataan mana yang benar tentang if-else di Java?',
                'opsi_a'         => 'if bisa tanpa else',
                'opsi_b'         => 'else wajib ada setelah if',
                'opsi_c'         => 'if tidak bisa di-nested',
                'opsi_d'         => 'else if tidak tersedia di Java',
                'jawaban_benar'  => 'a',
                'topik'          => 'percabangan',
                'tingkat_kesulitan' => 'sedang',
            ],
            [
                'stage_id'       => $stage5?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'pilihan_ganda',
                'pertanyaan'     => 'Berapa kali loop berikut akan dieksekusi?\nfor (int i = 0; i < 5; i++) { }',
                'opsi_a'         => '4 kali',
                'opsi_b'         => '6 kali',
                'opsi_c'         => '5 kali',
                'opsi_d'         => '0 kali',
                'jawaban_benar'  => 'c',
                'topik'          => 'for-loop',
                'tingkat_kesulitan' => 'sedang',
            ],

            // ─── URUTAN KODE ─────────────────────────────────────────────

            [
                'stage_id'       => $stage1?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'urutan_kode',
                'pertanyaan'     => 'Susun potongan kode berikut agar program Java mencetak "Hello, World!" dengan benar:',
                'kode_potongan'  => [
                    'public class Main {',
                    '    public static void main(String[] args) {',
                    '        System.out.println("Hello, World!");',
                    '    }',
                    '}',
                ],
                'topik'          => 'pengenalan-java',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'stage_id'       => $stage2?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'urutan_kode',
                'pertanyaan'     => 'Susun kode berikut agar deklarasi dan inisialisasi variabel int dilakukan dengan benar:',
                'kode_potongan'  => [
                    'public class Main {',
                    '    public static void main(String[] args) {',
                    '        int angka = 42;',
                    '        System.out.println(angka);',
                    '    }',
                    '}',
                ],
                'topik'          => 'variabel',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'stage_id'       => $stage4?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'urutan_kode',
                'pertanyaan'     => 'Susun kode if-else berikut agar program mencetak "Lulus" jika nilai >= 70:',
                'kode_potongan'  => [
                    'int nilai = 85;',
                    'if (nilai >= 70) {',
                    '    System.out.println("Lulus");',
                    '} else {',
                    '    System.out.println("Tidak Lulus");',
                    '}',
                ],
                'topik'          => 'percabangan',
                'tingkat_kesulitan' => 'sedang',
            ],
            [
                'stage_id'       => $stage5?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'urutan_kode',
                'pertanyaan'     => 'Susun kode for-loop berikut agar mencetak angka 1 sampai 3:',
                'kode_potongan'  => [
                    'for (int i = 1; i <= 3; i++) {',
                    '    System.out.println(i);',
                    '}',
                ],
                'topik'          => 'for-loop',
                'tingkat_kesulitan' => 'sedang',
            ],

            // ─── TULIS KODE ──────────────────────────────────────────────

            [
                'stage_id'       => $stage1?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'tulis_kode',
                'pertanyaan'     => "Perhatikan kode berikut:\n\nSystem.out.println(\"Belajar Java\");\n\nApa output yang dicetak oleh kode tersebut?",
                'expected_output' => 'Belajar Java',
                'topik'          => 'pengenalan-java',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'stage_id'       => $stage2?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'tulis_kode',
                'pertanyaan'     => "Perhatikan kode berikut:\n\nint a = 10;\nint b = 3;\nSystem.out.println(a + b);\n\nApa output yang dicetak?",
                'expected_output' => '13',
                'topik'          => 'variabel',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'stage_id'       => $stage3?->id,
                'tipe'           => 'placement',
                'tipe_soal'      => 'tulis_kode',
                'pertanyaan'     => "Perhatikan kode berikut:\n\nint x = 7;\nint y = 2;\nSystem.out.println(x % y);\n\nApa output yang dicetak?",
                'expected_output' => '1',
                'topik'          => 'operator',
                'tingkat_kesulitan' => 'sedang',
            ],
        ];

        foreach ($questions as $q) {
            Question::firstOrCreate(
                ['pertanyaan' => $q['pertanyaan'], 'tipe' => 'placement'],
                $q
            );
        }
    }
}
