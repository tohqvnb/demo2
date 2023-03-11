<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        $user = User::create(
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => app('hash')->make('admin123')
            ]
        );
        $user->assignRole('writer', 'admin');
    }
}
