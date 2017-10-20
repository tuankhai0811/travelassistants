<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulePlaceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schedules_place', function(Blueprint $table){
			$table->increments('id');
			$table->string('id_schedule');
			$table->string('id_place');
			$table->string('email');
			$table->string('date_start');
			$table->string('date_end');
			$table->string('length');
			$table->string('description');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('schedules_place');
	}

}
