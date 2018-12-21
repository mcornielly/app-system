<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->string('user_name')->unique();
            $table->string('password');
            $table->boolean('condition')->default(1);

            $table->rememberToken();
            // $table->timestamps();

            //Relations
            $table->foreign('id')->references('id')->on('clients')
                  ->onDelete('cascade');      
            $table->foreign('role_id')->references('id')->on('roles');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
