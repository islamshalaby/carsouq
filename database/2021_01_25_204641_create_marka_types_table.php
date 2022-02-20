<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkaTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marka_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image', 300);
            $table->string('title_en', 200);
            $table->string('title_ar', 200);
            $table->enum('deleted',['0','1'])->default('0');
            $table->bigInteger('marka_id')->unsigned()->nullable();
            $table->foreign('marka_id')->references('id')->on('markas')->onDelete('restrict');
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
        Schema::dropIfExists('marka_types');
    }
}
