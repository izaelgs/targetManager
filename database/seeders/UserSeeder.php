<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'izael',
            'email' => 'izael-123@outlook.com',
            'password' => bcrypt('izael'),
        ]);
    }
}
