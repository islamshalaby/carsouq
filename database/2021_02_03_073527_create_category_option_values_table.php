<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_option_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image', 255);
            $table->string('value_ar', 255);
            $table->string('value_en', 255);
            $table->bigInteger('option_id')->unsigned()->nullable();
            $table->foreign('option_id')->references('id')->on('category_options')->onDelete('restrict');
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
        Schema::dropIfExists('category_option_values');
    }
}
