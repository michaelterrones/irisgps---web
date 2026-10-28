<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAll extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    $tables = DB::select('SHOW TABLES');
        $db = 'Tables_in_'.Config::get('database.connections.mysql.database');
        $except = ['companies', 'migrations'];
        foreach ($tables as $tbx) {

        	if(!in_array($tbx->$db, $except))
        	{
	            Schema::table($tbx->$db, function($table)
	            {
	                //$table->string('created_by')->nullable();
	                //$table->string('updated_by')->nullable();
	                $table->string('subdomain')->nullable();
	            });
	        }
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
