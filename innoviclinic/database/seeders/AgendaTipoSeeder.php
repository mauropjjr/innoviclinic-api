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
            ['id' => 1, 'nome' => 'Consulta', 'sem_horario' => 0, 'sem_procedimento' => 0],
            ['id' => 2, 'nome' => 'Retorno', 'sem_horario' => 0, 'sem_procedimento' => 0],
            ['id' => 3, 'nome' => 'Exame', 'sem_horario' => 0, 'sem_procedimento' => 0],
            ['id' => 4, 'nome' => 'Encaixe', 'sem_horario' => 0, 'sem_procedimento' => 0],
            ['id' => 5, 'nome' => 'Procedimento', 'sem_horario' => 0, 'sem_procedimento' => 1]
        ];
        // foreach ($tipos as $value) {
        //     $value['usuario_id'] = 1;
        //     $value['created_at'] = date('Y-m-d H:i:s');
        //     $value['updated_at'] = date('Y-m-d H:i:s');
        //     DB::table('procedimento_tipos')->insert($value);
        // }
    }
}
