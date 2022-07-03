<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->text('title')->nullable();
            $table->longText('content')->nullable();
            $table->text('status')->nullable();
            $table->text('start_at')->nullable();
            $table->text('end_at')->nullable();
            $table->text('recurrence_rule')->nullable();
            $table->text('recurrence_exception')->nullable();
            $table->bigInteger('recurrence_id')->nullable();
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
        Schema::dropIfExists('schedules');
    }
}
