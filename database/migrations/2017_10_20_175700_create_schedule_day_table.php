<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleDayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schedules_day', function(Blueprint $table){
			$table->increments('id');
			$table->string('id_schedule');
			$table->string('id_schedule_place');
			$table->string('place_id');
			$table->string('email');
			$table->string('time_start');
			$table->string('time_end');
			$table->string('date');
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
		Schema::drop('schedules_day');
	}

}
