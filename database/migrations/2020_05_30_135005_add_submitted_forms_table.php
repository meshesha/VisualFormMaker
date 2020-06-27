<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubmittedFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_forms', function ($table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('content');
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submitted_forms');
    }
}
