<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectsTableFileHandlerFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('data_input_handler')->default('CHOOSE A HANDLER!');
            $table->string('data_output_handler')->default('CHOOSE A HANDLER!');
            $table->dropColumn('file_handler');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('file_handler');
            $table->dropColumn('data_input_handler');
            $table->dropColumn('data_output_handler');
        });
    }
}
