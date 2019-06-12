<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblHujan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TblHujan', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->float('nilai_analog', 8, 2);
            $table->boolean('nilai_digital')->nullable()->default(0);
            // $table->timestampTz('expired_at')->nullable();
            $table->timestampsTz();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('TblHujan');

        Schema::enableForeignKeyConstraints();
    }
}
