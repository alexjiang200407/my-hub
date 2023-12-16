<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reminderId')->unsigned();
            $table->string('tag');
            $table->timestamps();

            $table->foreign('reminderId')->references('id')->on('reminders')
                ->onDelete('cascade');
            // $table->unique(['reminderId','tag']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
