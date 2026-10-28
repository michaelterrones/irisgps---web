<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		
		
		$this->call('UserTypesTableSeeder');
		$this->call('JobsTableSeeder');
		$this->call('PassengerTypesTableSeeder');
		$this->call('IncidentTypesTableSeeder');
		$this->call('TaskStatesTableSeeder');
		$this->call('PolygonsTableSeeder');
		
		//Test Seeding
		if(Config::get('app.test_seeding', false))
		{
			$this->call('CompaniesTableSeeder');
			$this->call('CustomersTableSeeder');
			$this->call('DevicesTableSeeder');
			$this->call('UsersTableSeeder');
			$this->call('VehiclesTableSeeder');
			$this->call('EmployeesTableSeeder');
			$this->call('TrackingRoutesTableSeeder');
			$this->call('RouteTasksTableSeeder');
			$this->call('PassengersTableSeeder');
		}
		
	}

}
