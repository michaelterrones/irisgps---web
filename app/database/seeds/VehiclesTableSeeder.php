<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class VehiclesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 40) as $index)
		{
			$faker->seed($index);
			Vehicle::create([
				'plate' => strtoupper($faker->bothify('???-###')),
				'brand' => $faker->randomElement($brands = array ('Volvo', 'Nissan', 'Toyota', 'Susuki', 'Honda', 'Hyundai', 'Mazda', 'MG')),
				'model' => $faker->randomElement($models = array ('MF5','GT','X10', 'JT3', '3', '5', 'S10', '7N', 'FF', 'R92')),
				'color' => $faker->randomElement($colors = array ('Rojo','Blanco','Verde', 'Negro', 'Azul', 'Amarillo', 'Naranja'))
			]);
		}
	}

}