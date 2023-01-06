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
                'name' => 'Dev Eika',
                'cpf' => '',
                'role_id' => '1',
                'status' => 1,
                'email' => 'dev@agenciaeika.com',
                'password' => bcrypt('654321'),
                'remember_token' => '',
            ],
            [
                'id' => 2,
                'name' => 'Agência Eika',
                'cpf' => '',
                'role_id' => '1',
                'status' => 1,
                'email' => 'contato@agenciaeika.com',
                'password' => bcrypt('@243838Senha'),
                'remember_token' => '',
            ],

        ];

        DB::table('users')->insert($users);
    }
}
