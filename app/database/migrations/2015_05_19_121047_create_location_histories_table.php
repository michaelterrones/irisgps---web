<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location_histories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('vehicle_id')->unsigned();
			$table->integer('tracking_route_id')->unsigned()->nullable();
			$table->decimal('lat',17,15);
			$table->decimal('lng',17,15);
			$table->integer('incident_type_id')->unsigned()->nullable();
			$table->string('incident_description')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('vehicle_id')->references('id')->on('vehicles');
			$table->foreign('tracking_route_id')->references('id')->on('tracking_routes');
			$table->foreign('incident_type_id')->references('id')->on('incident_types');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('location_histories');
	}

}
