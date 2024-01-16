<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profissoes = [
            ['nome' => 'Biomédico'],
            ['nome' => 'Dentista'],
            ['nome' => 'Educador Físico'],
            ['nome' => 'Enfermeiro(a)'],
            ['nome' => 'Estética'],
            ['nome' => 'Farmacêutico'],
            ['nome' => 'Fisioterapeuta'],
            ['nome' => 'Fonoaudiólogo'],
            ['nome' => 'Massagem Terapêutica'],
            ['nome' => 'Médico'],
            ['nome' => 'Nutricionista'],
            ['nome' => 'Podologia'],
            ['nome' => 'Protético'],
            ['nome' => 'Psicanalista'],
            ['nome' => 'Psicólogo'],
            ['nome' => 'Psicopedagoga'],
            ['nome' => 'Quiropraxista'],
            ['nome' => 'Radiologia Intervencionista'],
            ['nome' => 'Terapeuta Clínico'],
            ['nome' => 'Terapeuta Complementar'],
            ['nome' => 'Terapeuta Ocupacional'],
        ];
        foreach ($profissoes as $value) {
            $value['usuario_id'] = 1;
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('profissoes')->insert($value);
        }
    }
}
