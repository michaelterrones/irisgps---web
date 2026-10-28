<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class RouteTasksTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		$taskIder = 1;

		foreach(range(1, 200) as $index)
		{
			$faker->seed($index);

			$customers = [];

			$customerToVisit = $faker->numberBetween($min = 1, $max = 8);

			while(count($customers) <= $customerToVisit)
			{
				$rndId = $faker->numberBetween($min = 1, $max = 91);
				if(!in_array($rndId, $customers))
				{
					array_push($customers, $rndId);
				}
			}

			foreach($customers as $customer_id)
			{
				RouteTask::create([
					'tracking_route_id' => $index,
					'task_state_id' => 1,
					'customer_id' => $customer_id,
					'description' => $faker->paragraph(2)
				]);

				TaskStateHistory::create([
					'route_task_id' => $taskIder,
					'task_state_id' => 1,
					'description'	=> 'Programado por el sistema.'
				]);

				$taskIder++;
			}
		}
	}

}