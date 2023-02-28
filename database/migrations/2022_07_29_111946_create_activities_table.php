<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('body');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mail_id')->constrained()->cascadeOnDelete();
            $table->string('send_number')->nullable();
            $table->timestamp('send_date')->nullable();
            $table->string('send_destination')->nullable();
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
        Schema::dropIfExists('activities');
    }
};
