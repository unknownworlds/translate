<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminFieldsToTranslatedStringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('translated_strings', function (Blueprint $table) {
            $table->integer('accepted_by')->unsigned()->nullable();
            $table->foreign('accepted_by')->references('id')->on('users')->onDelete('set null');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('translated_strings', function (Blueprint $table) {
            $table->dropColumn('accepted_by');
            $table->dropColumn('deleted_by');
        });
    }
}
