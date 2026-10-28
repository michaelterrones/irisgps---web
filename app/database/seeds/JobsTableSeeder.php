<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class JobsTableSeeder extends Seeder {

	public function run()
	{
		Job::create(['name' => 'Técnico']);
		Job::create(['name' => 'Instalador']);
		Job::create(['name' => 'Auxiliar']);
	}

}