<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->string('abuse_type')->nullable();
            $table->integer('domain_id')->unsigned();
            $table->foreign('domain_id')->references('id')->on('domains');
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
        Schema::dropIfExists('infections');
    }
}
