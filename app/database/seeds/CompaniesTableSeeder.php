<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class CompaniesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		Company::create([
			'name' => 'JMTECH S.A.C.',
			'ruc' => 20563402891,
			'valid_until' => $faker->dateTime('2099-06-30 23:59:59'),
			'subdomain' => 'jmtech'
			]);		

		Company::create([
			'name' => 'AMERICA MOVIL PERU S.A.C.',
			'ruc' => 20467534026,
			'valid_until' => $faker->dateTime('2015-06-30 23:59:59'),
			'subdomain' => 'claro'
			]);
	/*
		foreach(range(1, 10) as $index)
		{
			Company::create([

			]);
		}
	*/
	}

}