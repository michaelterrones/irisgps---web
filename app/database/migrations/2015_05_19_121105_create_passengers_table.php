<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePassengersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('passengers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tracking_route_id')->unsigned();
			$table->integer('employee_id')->unsigned();
			$table->integer('passenger_type_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('tracking_route_id')->references('id')->on('tracking_routes');
			$table->foreign('employee_id')->references('id')->on('employees');
			$table->foreign('passenger_type_id')->references('id')->on('passenger_types');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('passengers');
	}

}
