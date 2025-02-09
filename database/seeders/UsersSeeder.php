<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'John Doe'],
            ['name' => 'Jane Smith'],
            ['name' => 'Alice Johnson'],
            ['name' => 'Bob Brown'],
            ['name' => 'Charlie Davis'],
            ['name' => 'Tony Stark'],
            ['name' => 'Will smith'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
