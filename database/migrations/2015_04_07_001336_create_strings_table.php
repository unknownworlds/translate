<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStringsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('translated_strings', function(Blueprint $table) {
            $table->increments('id');

            $table->integer('project_id')->unsigned()->index();
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->integer('language_id')->unsigned()->index();
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');

            $table->integer('base_string_id')->unsigned()->index();
			$table->foreign('base_string_id')->references('id')->on('base_strings')->onDelete('cascade');

            $table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('text');
            $table->integer('up_votes')->default(0);
            $table->integer('down_votes')->default(0);
            $table->boolean('is_accepted')->default(false);
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
		Schema::drop('translated_strings');
	}

}
