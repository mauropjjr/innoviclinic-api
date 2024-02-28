<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgendaTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['id' => 1,'empresa_id'=> 1, 'cor'=>'#0066FF', 'nome' => 'Consulta', 'sem_horario' => 0, 'sem_procedimento' => 0],
            ['id' => 2,'empresa_id'=> 1, 'cor'=>'#FFFF00', 'nome' => 'Retorno', 'sem_horario' => 0, 'sem_procedimento' => 0],
            ['id' => 3,'empresa_id'=> 1, 'cor'=>'#00FF00', 'nome' => 'Exame', 'sem_horario' => 0, 'sem_procedimento' => 0],
            ['id' => 4,'empresa_id'=> 1, 'cor'=>'#FF00FF', 'nome' => 'Encaixe', 'sem_horario' => 0, 'sem_procedimento' => 0],
            ['id' => 5,'empresa_id'=> 1, 'cor'=>'#800080', 'nome' => 'Procedimento', 'sem_horario' => 0, 'sem_procedimento' => 1]
        ];
         foreach ($tipos as $value) {
            $value['usuario_id'] = 1;
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('agenda_tipos')->insert($value);
        }
    }
}
