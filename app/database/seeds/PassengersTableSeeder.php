<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class PassengersTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 200) as $tracking_route_id)
		{
			$faker->seed($tracking_route_id);

			$passengers = [];

			$pilot = true;

			while(count($passengers) <= 3)
			{
				$rndId = $faker->numberBetween($min = 1, $max = 120);
				if(!in_array($rndId, $passengers))
				{
					array_push($passengers, $rndId);
				}
			}

			foreach($passengers as $employee_id)
			{

				if($pilot)
				{
					$passenger_type_id = 1;
					$pilot = false;
				}else{
					$passenger_type_id = 2;
				}

				Passenger::create([
					'tracking_route_id' 	=> $tracking_route_id,
					'employee_id'	=> $employee_id,
					'passenger_type_id'	=> $passenger_type_id
				]);
			}
		}
	}

}