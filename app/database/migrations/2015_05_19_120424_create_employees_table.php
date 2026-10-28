<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employees', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('dni', 8)->unique();
			$table->string('first_name');
			$table->string('last_name');
			$table->string('mobile');
			$table->integer('job_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('job_id')->references('id')->on('jobs');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employees');
	}

}
