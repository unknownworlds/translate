<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAlternativeToTranslatedStringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table( 'translated_strings', function ( Blueprint $table ) {
		    $table->boolean( 'alternative_or_empty' )->unsigned()->default( 0 );
	    } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table( 'translated_strings', function ( Blueprint $table ) {
		    $table->dropColumn( 'alternative_or_empty' );
	    } );
    }
}
