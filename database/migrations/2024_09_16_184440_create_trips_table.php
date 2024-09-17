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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->dateTime("departure");
            $table->dateTime("arrival");
            $table->foreignId("bus_id")->constrained();
            $table->timestamps();
        });

        Schema::create('trip_segments', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("order");
            $table->boolean("is_main")->default(false);
            $table->foreignId("trip_id");
            $table->foreignId("start_station_id")->constrained("stations")
                ->cascadeOnDelete();
            $table->foreignId("end_station_id")->constrained("stations")
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
