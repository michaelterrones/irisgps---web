<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRouteTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('route_tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tracking_route_id')->unsigned();
			$table->integer('task_state_id')->unsigned();
			$table->integer('customer_id')->unsigned();
			$table->string('description', 2000)->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('tracking_route_id')->references('id')->on('tracking_routes');
			$table->foreign('task_state_id')->references('id')->on('task_states');
			$table->foreign('customer_id')->references('id')->on('customers');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('route_tasks');
	}

}
