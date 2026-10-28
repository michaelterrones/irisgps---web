<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('customer_code')->unique();
			$table->string('name');
			$table->string('phone');
			$table->string('mobile')->nullable();
			$table->decimal('lat', 17, 15);
			$table->decimal('lng', 17, 15);
			$table->string('address')->nullable();
			$table->string('district')->nullable();
			$table->string('province')->nullable();
			$table->string('region')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customers');
	}

}
