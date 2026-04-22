<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClmQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clm_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('clm_id');
            $table->string('question');
            $table->string('ques_type');
            $table->string('input_id');
            $table->string('input_name');
            $table->string('select_option');
            $table->string('event_type');
            $table->string('event_function');
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
        Schema::dropIfExists('clm_questions');
    }
}
