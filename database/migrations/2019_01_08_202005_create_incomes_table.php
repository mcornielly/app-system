<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('provider_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('type_voucher', 20);
            $table->string('serie_voucher', 7)->nullable();
            $table->string('num_voucher', 10);
            $table->dateTime('date_hour');
            $table->decimal('tax', 4, 2);
            $table->decimal('total', 11,2);
            $table->string('state', 20);
            
            $table->timestamps();

            //Relations
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
