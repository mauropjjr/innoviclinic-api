<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcedimentoTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['id' => 1, 'nome' => 'Consulta'],
            ['id' => 2, 'nome' => 'Retorno'],
            ['id' => 3, 'nome' => 'Exame'],
            ['id' => 4, 'nome' => 'Encaixe'],
            ['id' => 5, 'nome' => 'Procedimento']
        ];
        foreach ($tipos as $value) {
            $value['usuario_id'] = 1;
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('procedimento_tipos')->insert($value);
        }
    }
}
