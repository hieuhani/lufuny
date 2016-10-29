<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
          [
              'name' => 'administrator',
              'description' => 'General management'
          ],
            [
                'name' => 'moderator',
                'description' => 'Content management'
            ]
        ];

        foreach ($roles as $role) {
            App\Role::create($role);
        }
    }
}
