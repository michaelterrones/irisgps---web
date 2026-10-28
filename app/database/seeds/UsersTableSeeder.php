<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();
			
		User::create([
			'email' => 'superadminsistema@yopmail.com',
			'password' => Hash::make('password'),
			'first_name' => 'Super Administrador de Sistema',
			'last_name' => 'JMTech',
			'user_type_id' => 1,
			'company_id' => 1
		]);

		User::create([
			'email' => 'adminsistema@yopmail.com',
			'password' => Hash::make('password'),
			'first_name' => 'Administrador de Sistema',
			'last_name' => 'JMTech',
			'user_type_id' => 2,
			'company_id' => 1
		]);

		User::create([
			'email' => 'superadmin@yopmail.com',
			'password' => Hash::make('password'),
			'first_name' => 'Super Administrador',
			'last_name' => 'Claro',
			'user_type_id' => 3,
			'company_id' => 2
		]);

		User::create([
			'email' => 'admin@yopmail.com',
			'password' => Hash::make('password'),
			'first_name' => 'Administrador',
			'last_name' => 'Claro',
			'user_type_id' => 4,
			'company_id' => 2
		]);

		foreach(range(1, 10) as $index)
		{
			User::create([
				'email' => "usuario$index@yopmail.com",
				'password' => Hash::make('password'),
				'first_name' => "Usuario $index",
				'last_name' => 'Claro',
				'user_type_id' => 5,
				'company_id' => 2
			]);
		}
	}

}