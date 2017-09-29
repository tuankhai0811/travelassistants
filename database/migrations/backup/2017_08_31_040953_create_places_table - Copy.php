<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('places', function(Blueprint $table) {
			$table->increments('_id');
			$table->string('id', 5)->unique();
			$table->string('long_name');
			$table->string('short_name');
			$table->float('rating', 2, 2);
			$table->text('address');
			$table->string('province_name');
			$table->string('province_id', 2);
			$table->string('phone');
			$table->string('postal_code', 6);
			$table->double('location_lat');
			$table->double('location_lng');
			$table->text('icon');
			$table->text('logo_3_4');
			$table->text('logo_4_3');
			$table->text('logo_16_9');
			$table->json('opening_hours');
			$table->json('photos');
			$table->text('website');
			$table->integer('type_sea');
			$table->integer('type_attractions');
			$table->integer('type_entertainment');
			$table->integer('type_cultural');
			$table->integer('type_spring');
			$table->integer('type_summer');
			$table->integer('type_autumn');
			$table->integer('type_winter');
			$table->longText('description');
			$table->json('hotels');
			$table->json('restaurants');
			$table->json('foods');
			$table->json('game');
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
		Schema::drop('places');
	}

}
