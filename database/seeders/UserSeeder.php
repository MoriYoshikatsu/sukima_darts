<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DateTime;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 1; $i <= 5; $i++) {
			$name = 'test' . $i;
			$email = 'test' . $i . '@gmail.com';
		
			DB::table('users')->insert([
				'name' => $name,
				'email' => $email,
				'password' => Hash::make('1234567890'),
				'created_at' => new DateTime(),
				'updated_at' => new DateTime(),
			]);
		}
	}
}
