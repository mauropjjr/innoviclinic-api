<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgendaStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['id' => 1, 'nome' => 'Confirmar'],
            ['id' => 2, 'nome' => 'Confirmado'],
            ['id' => 3, 'nome' => 'Cancelado'],
            ['id' => 4, 'nome' => 'Chegou'],
            ['id' => 5, 'nome' => 'Em atendimento'],
            ['id' => 6, 'nome' => 'Atendido'],
            ['id' => 7, 'nome' => 'Faltou'],
        ];
        foreach ($tipos as $value) {
            $value['usuario_id'] = 1;
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('agenda_status')->insert($value);
        }
    }
}
