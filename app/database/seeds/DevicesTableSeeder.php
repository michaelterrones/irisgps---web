<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class DevicesTableSeeder extends Seeder {

	public function run()
	{
		Device::create([
				'mobile' => '966464512',
				'allowed' => 1,
				'activated' => 1
			]);

		Device::create([
				'mobile' => '999888777',
				'allowed' => 1,
				'activated' => 1
			]);

		Device::create([
				'mobile' => '999000111',
				'allowed' => 1,
				'activated' => 1
			]);
		/*
		$faker => Faker::create();

		foreach(range(1, 10) as $index)
		{

		}
		*/
	}

}