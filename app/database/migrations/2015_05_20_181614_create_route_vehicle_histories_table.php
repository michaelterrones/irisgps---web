<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRouteVehicleHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('route_vehicle_histories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tracking_route_id')->unsigned();
			$table->integer('vehicle_id')->unsigned();
			$table->string('description', 1000);
			$table->timestamps();

			$table->foreign('tracking_route_id')->references('id')->on('tracking_routes');
			$table->foreign('vehicle_id')->references('id')->on('vehicles');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('route_vehicle_histories');
	}

}
