<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UserTypesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();
		
		UserType::create(['name'=>'Super Administrador de Sistema']);
		UserType::create(['name'=>'Administrador de Sistema']);
		UserType::create(['name'=>'Super Administrador']);
		UserType::create(['name'=>'Administrador']);
		UserType::create(['name'=>'Usuario']);
	}

}