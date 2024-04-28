<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id')->unsigned();
            $table->integer('class_id')->unsigned();
            $table->integer('units')->unsigned();
            $table->integer('academic_period_id')->unsigned();
            $table->timestamps();

            $table->foreign('academic_period_id')
                ->references('id')
                ->on('academic_periods')
                ->onDelete('cascade');

            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
                ->onDelete('cascade');

            $table->foreign('class_id')
                ->references('id')
                ->on('classes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects_classes');
    }
}
