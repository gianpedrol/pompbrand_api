<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stages = [
            [
                "stage" => "teste 1",
                "phase_id" => 1,
            ],
            [
                "stage" => "teste 2",
                "phase_id" => 1,
            ],
            [
                "stage" => "teste 3",
                "phase_id" => 1,
            ],
            [
                "stage" => "teste 1",
                "phase_id" => 2,
            ],
            [
                "stage" => "teste 2",
                "phase_id" => 2,
            ],
            [
                "stage" => "teste 3",
                "phase_id" => 2,
            ],
            [
                "stage" => "teste 1",
                "phase_id" => 3,
            ],
            [
                "stage" => "teste 2",
                "phase_id" => 3,
            ],
            [
                "stage" => "teste 3",
                "phase_id" => 3,
            ],
         
        ];

        DB::table('stages')->insert($stages);
    }
}
