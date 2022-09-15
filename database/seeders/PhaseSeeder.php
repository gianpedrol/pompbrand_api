<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class PhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $phases = [
            [
                'phase_name' => 'Fase 1',
            ],
            [
                'phase_name' => 'Fase 2',
            ],
            [
                'phase_name' => 'Fase 3',
            ],

        ];

        DB::table('phases')->insert($phases);
    }
}
