<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

		for ($i = 1; $i <= 2; $i++) {
			for ($j = 1; $j <= 3; $j++) {
				$items[] = [
					'user_id' => $i,
					'name' => 'Тег ' . $j,
				];
			}
		}

		DB::table('tags')->insert($items);
    }
}
