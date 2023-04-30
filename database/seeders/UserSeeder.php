<?php

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = now();

		$faker = Container::getInstance()->make(Generator::class);

		$items[] = [
			'name' => 'Иван Первый',
			'email' => 'ivan@example.com',
			'email_verified_at' => now(),
			'password' => Hash::make('12345j'),
			'remember_token' => Str::random(10),
			'created_at' => $date,
			'updated_at' => $date,
		];

		$items[] = [
			'name' => 'Марина Вторая',
			'email' => 'mari@example.com',
			'email_verified_at' => now(),
			'password' => Hash::make('12345j'),
			'remember_token' => Str::random(10),
			'created_at' => $date,
			'updated_at' => $date,
		];

		for ($i = 0; $i <= 2; $i++) {
			$items[] = [
				'name' => $faker->name(),
				'email' => $faker->unique()->safeEmail(),
				'email_verified_at' => now(),
				'password' => Hash::make('12345j'),
				'remember_token' => Str::random(10),
				'created_at' => $date,
				'updated_at' => $date,
			];
		}

		DB::table('users')->insert($items);
    }
}
