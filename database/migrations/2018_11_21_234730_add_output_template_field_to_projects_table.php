<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOutputTemplateFieldToProjectsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table( 'projects', function ( Blueprint $table ) {
			$table->text( 'output_template' )->nullable();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table( 'projects', function ( Blueprint $table ) {
			$table->dropColumn( 'output_template' );
		} );
	}
}
