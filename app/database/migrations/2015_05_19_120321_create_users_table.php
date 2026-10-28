<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('password');
			$table->string('first_name');
			$table->string('last_name');
			$table->integer('user_type_id')->unsigned();
			$table->integer('company_id')->unsigned();
			$table->rememberToken();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('user_type_id')->references('id')->on('user_types');
			$table->foreign('company_id')->references('id')->on('companies');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
