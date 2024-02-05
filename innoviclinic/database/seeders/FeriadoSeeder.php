<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FeriadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$ano = 2026;
        for ($ano = 2024; $ano < 2027; $ano++) {
            $lista = Http::get('https://brasilapi.com.br/api/feriados/v1/'.$ano)->json();
            foreach ($lista as $value) {
                $feriado = [
                    'data' => $value['date'],
                    'nome' => $value['name'],
                    'descricao' => $value['name'],
                    'tipo' => 'Feriado Nacional',
                    'usuario_id' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                DB::table('feriados')->insert($feriado);
            }
        }
    }
}
