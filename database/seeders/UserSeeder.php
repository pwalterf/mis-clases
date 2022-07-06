<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teacher = User::create([
            'firstname' => 'Milagro',
            'lastname' => 'Saavedra',
            'dni' => 38827420,
            'birthdate' => '1995-04-01',
            'email' => 'admin@test.com',
            'password' => Hash::make('admintest'),
        ]);

        $teacher->assignRole('admin');

        $student = User::create([
            'firstname' => 'Walter',
            'lastname' => 'Pinto',
            'dni' => 36194266,
            'birthdate' => '1991-04-16',
            'email' => 'student@test.com',
            'password' => Hash::make('admintest'),
        ]);

        $student->assignRole('user');

        $student2 = User::create([
            'firstname' => 'Antonella',
            'lastname' => 'Pinto',
            'dni' => 40194266,
            'birthdate' => '1991-04-16',
            'email' => 'student2@test.com',
            'password' => Hash::make('admintest'),
        ]);

        $student2->assignRole('user');

        $student3 = User::create([
            'firstname' => 'Miguel',
            'lastname' => 'Pinto',
            'dni' => 38194266,
            'birthdate' => '1991-04-16',
            'email' => 'student3@test.com',
            'password' => Hash::make('admintest'),
        ]);

        $student3->assignRole('user');
    }
}
