<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class TrackingRoutesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 40) as $vehicleId)
		{
			foreach(range(1, 5) as $routeId)
			{
				TrackingRoute::create([
					'date' => Carbon::now()->addDays($routeId-1),
					'vehicle_id' => $vehicleId
				]);
			}
		}
	}

}