<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EscolaridadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $escolaridades = [
            ['nome' => 'Sem Formação'],
            ['nome' => 'Ensino Fundamental Incompleto'],
            ['nome' => 'Ensino Fundamental Completo'],
            ['nome' => 'Ensino Médio Incompleto'],
            ['nome' => 'Ensino Médio Completo'],
            ['nome' => 'Ensino Superior Incompleto'],
            ['nome' => 'Ensino Superior Completo'],
            ['nome' => 'Especialização / Pós-Graduação'],
            ['nome' => 'Especialização'],
            ['nome' => 'Mestrado Completo'],
            ['nome' => 'Doutorado Completo'],
        ];
        foreach ($escolaridades as $value) {
            $value['usuario_id'] = 1;
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('escolaridades')->insert($value);
        }
    }
}
