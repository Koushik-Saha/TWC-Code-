<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bsoft_users')->insert([
            'role_id'           => 1,
            'name'              => 'BD SOFT IT',
            'email'             => 'admin@email.com',
            'username'          => 'bdsoft',
            'mobile'            => '1911343443',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('11111111'),
            'can_login'         => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('bsoft_users')->insert([
            'role_id'           => 2,
            'name'              => 'Office Manager',
            'email'             => 'manager@email.com',
            'username'          => 'manager',
            'mobile'            => '1911223344',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('11111111'),
            'can_login'         => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('bsoft_users')->insert([
            'role_id'           => 3,
            'name'              => 'Project Manager',
            'email'             => 'project_manager@email.com',
            'username'          => 'project_manager',
            'mobile'            => '1922334455',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('11111111'),
            'can_login'         => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('bsoft_users')->insert([
            'role_id'           => 4,
            'name'              => 'Accountant',
            'email'             => 'accountant@email.com',
            'username'          => 'accountant',
            'mobile'            => '1933445566',
            'email_verified_at' => Carbon::now(),
            'password'          => Hash::make('11111111'),
            'can_login'         => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('bsoft_users')->insert([
            'role_id'           => 5,
            'name'              => 'Mr. Client',
            'email'             => 'client@email.com',
            'username'          => 'client',
            'mobile'            => '1922334477',
            'can_login'         => 0,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        DB::table('bsoft_users')->insert([
            'role_id'           => 6,
            'name'              => 'Mr. Labour',
            'email'             => 'labour@email.com',
            'username'          => 'labour',
            'mobile'            => '1922334499',
            'can_login'         => 0,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
    }
}
