<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = App\User::create([
            'email' => '658655@gmail.com',
            'name' => 'Hieu Tran',
            'password' => Hash::make('123456')
        ]);
        $roleAdmin = \App\Role::where('name', 'administrator')->first();
        $user->roles()->attach($roleAdmin->id);
    }
}
