<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class PassengerTypesTableSeeder extends Seeder {

	public function run()
	{
		PassengerType::create(['name' => 'Chofer']);
		PassengerType::create(['name' => 'Pasajero']);
	}

}