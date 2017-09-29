<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvincesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('provinces', function(Blueprint $table) {
			$table->increments('_id');
			$table->string('id', 2)->unique();
			$table->string('name')->unique();
			$table->integer('num_place');
			$table->json('places');
			$table->double('location_lat');
			$table->double('location_lng');
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
		//
		Schema::drop('provinces');
	}

}
