<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Pomp Brand',
                'cpf' => '41146470827',
                'role_id' => '1',
                'status' => 1,
                'email' => 'dev@pompbrand.com.br',
                'password' => bcrypt('654321'),
                'remember_token' => '',
            ],

        ];

        DB::table('users')->insert($users);
    }
}
