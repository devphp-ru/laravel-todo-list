<?php

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$items = [];

		for ($i = 1; $i <= 3; $i++) {
			$items[] = [
				'name' => 'Тег ' . $i,
			];
		}

		DB::table('tags')->insert($items);
    }
}
