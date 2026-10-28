<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskStateHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_state_histories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('route_task_id')->unsigned();
			$table->integer('task_state_id')->unsigned();
			$table->string('description');
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('route_task_id')->references('id')->on('route_tasks');
			$table->foreign('task_state_id')->references('id')->on('task_states');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_state_histories');
	}

}
