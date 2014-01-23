<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarouselTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fbf_carousel_panels', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->text('description');
			$table->string('link_url');
			$table->string('link_text');
			$table->string('background_image');
			$table->string('icon_image');
			$table->string('css_class');
			$table->integer('order')->unsigned()->nullable();
			$table->enum('status', array('DRAFT', 'APPROVED'))->default('DRAFT');
			$table->dateTime('published_date');
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
		Schema::drop('fbf_carousel_panels');
	}

}
