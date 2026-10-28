<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTrackingRoutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tracking_routes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->dateTime('date');
			$table->integer('vehicle_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$table->unique(array('date','vehicle_id'));
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
		Schema::drop('tracking_routes');
	}

}
