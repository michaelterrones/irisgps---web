<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class TaskStatesTableSeeder extends Seeder {

	public function run()
	{

		TaskState::create(['name' => 'Programada']);
		TaskState::create(['name' => 'Realizada']);
		TaskState::create(['name' => 'Pospuesta']);
		TaskState::create(['name' => 'Cancelada']);
		TaskState::create(['name' => 'Reprogramar']);

	}

}