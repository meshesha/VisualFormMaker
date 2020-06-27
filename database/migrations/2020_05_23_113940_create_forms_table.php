<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->integer('visibility_type')->default(1); //public , group, department , ...
            $table->string('visibility_to'); //group_id or department id
            $table->string('admin_users'); //array of user_id
            $table->boolean('allows_edit')->default(false);
            $table->string('identifier')->unique();
            $table->text('form_builder_json')->nullable();
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            //$table->foreign('id')->references('id')->on('submitted_forms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
}
