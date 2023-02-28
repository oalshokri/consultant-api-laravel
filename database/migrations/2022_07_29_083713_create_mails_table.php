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
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('description')->nullable();
            $table->foreignId('sender_id')->constrained()->cascadeOnDelete();
            $table->string('archive_number');
            $table->timestamp('archive_date');
            $table->string('decision')->nullable();
            $table->foreignId('status_id')->constrained()->cascadeOnDelete();
            $table->string('final_decision')->nullable();
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
        Schema::dropIfExists('mails');
    }
};
