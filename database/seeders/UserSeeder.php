<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed user table in database.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'firstname' => 'John',
                'lastname' => 'Doe',
            ]
        );
    }
}
