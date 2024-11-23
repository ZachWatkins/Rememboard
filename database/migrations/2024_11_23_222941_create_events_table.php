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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 255);
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('city');
            $table->integer('state');
            $table->string('folder_name', 255);
            $table->boolean('show_on_countdown')->default(false);
            $table->boolean('is_trip')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
