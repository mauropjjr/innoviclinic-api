<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class EspecialidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profissoes = [
            ['id' => 1, 'nome' => 'Biomédico'],
            ['id' => 2, 'nome' => 'Dentista'],
            ['id' => 3, 'nome' => 'Educador Fisico'],
            ['id' => 4, 'nome' => 'Enfermeiro(a)'],
            ['id' => 5, 'nome' => 'Estética'],
            ['id' => 6, 'nome' => 'Farmacêutico'],
            ['id' => 7, 'nome' => 'Fisioterapeuta'],
            ['id' => 8, 'nome' => 'Fonoaudiólogo'],
            ['id' => 9, 'nome' => 'Massagem Terapêutica'],
            ['id' => 10, 'nome' => 'Medico'],
            ['id' => 11, 'nome' => 'Nutricionista'],
            ['id' => 12, 'nome' => 'Podologia'],
            ['id' => 13, 'nome' => 'Protético'],
            ['id' => 14, 'nome' => 'Psicanalista'],
            ['id' => 15, 'nome' => 'Psicólogo'],
            ['id' => 16, 'nome' => 'Psicopedagoga'],
            ['id' => 17, 'nome' => 'Quiropraxista'],
            ['id' => 18, 'nome' => 'Radiologia Intervencionista'],
            ['id' => 19, 'nome' => 'Terapeuta Clinico'],
            ['id' => 20, 'nome' => 'Terapia Complementar'],
            ['id' => 21, 'nome' => 'Terapia Ocupacional'],
        ];

        $lista = Http::get('https://www.consulteaqui.com/baseAPI/TipoEspecialidades/getTipoEspecialidades.json')->json();
        $profissoesNaoEncontrada = [];
        foreach ($lista['Response'] as $value) {
            $profissao = $value['TipoProfissional']['nome'];

            $chaves = array_column($profissoes, 'nome');
            $indice = array_search($profissao, $chaves);
            if($indice !== false && isset($profissoes[$indice]['id'])){
                $especialidade = [
                    'nome' => trim($value['TipoEspecialidade']['nome']),
                    'profissao_id' => $profissoes[$indice]['id'],
                    'usuario_id' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                DB::table('especialidades')->insert($especialidade);
            } else {
                $profissoesNaoEncontrada[] = $profissao;
            }
        }

        if($profissoesNaoEncontrada){
            Log::info('Erro EspecialidadeSeeder: Não foi encontrado esses termos de profissões parametrizados no seed, são eles: '.join(', ', array_unique($profissoesNaoEncontrada)));
        }
    }
}
