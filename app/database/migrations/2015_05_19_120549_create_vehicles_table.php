<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVehiclesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('plate')->unique();
			$table->string('brand');
			$table->string('model');
			$table->string('color');
			$table->integer('device_id')->unsigned()->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('device_id')->references('id')->on('devices');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicles');
	}

}
