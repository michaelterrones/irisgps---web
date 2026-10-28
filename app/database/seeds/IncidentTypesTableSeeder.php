<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class IncidentTypesTableSeeder extends Seeder {

	public function run()
	{
			IncidentType::create(['name' => 'Avería del vehículo']);
			IncidentType::create(['name' => 'Robo/hurto de equipo o instrumentos']);
			IncidentType::create(['name' => 'Pasajero abandona vehículo']);
			IncidentType::create(['name' => 'Accidente de tráfico']);
	}

}