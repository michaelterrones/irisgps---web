<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class EmployeesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 120) as $index)
		{
			$faker->seed($index);
			Employee::create([
				'dni' => strtoupper($faker->numerify('########')),
				'first_name' => $faker->firstName,
				'last_name' => $faker->lastName,
				'mobile' => strtoupper($faker->bothify('9########')),
				'job_id' => $faker->numberBetween($min = 1, $max = 3)
			]);
		}
	}

}