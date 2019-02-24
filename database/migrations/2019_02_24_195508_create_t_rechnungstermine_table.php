<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRechnungstermineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_rechnungstermine', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('billNumber');
            $table->float('amount');
            $table->string('purpose')->nullable();
            $table->date('date');
            $table->integer('company_ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_rechnungstermine');
    }
}
