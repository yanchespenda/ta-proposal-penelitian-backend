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
        Schema::create('tblhujan', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->float('nilai_hujan_analog', 6, 2)->nullable()->default(0);
            $table->boolean('nilai_hujan_digital')->nullable()->default(0);
            $table->float('nilai_lembab', 5, 2)->nullable()->default(0);
            $table->float('nilai_suhu_c', 5, 2)->nullable()->default(0);
            $table->float('nilai_suhu_f', 5, 2)->nullable()->default(0);
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

        Schema::dropIfExists('tblhujan');

        Schema::enableForeignKeyConstraints();
    }
}
