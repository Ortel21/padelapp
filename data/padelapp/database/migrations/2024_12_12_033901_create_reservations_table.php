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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id(); // ID único para la reserva
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID del usuario que realiza la reserva
            $table->enum('court_number', ['1', '2']); // Número de pista
            $table->dateTime('start_time'); // Hora de inicio de la reserva
            $table->integer('duration_minutes'); // Duración de la reserva en minutos (90, 120, etc.)
            $table->enum('status', ['free', 'cancelled', 'filled'])->default('free'); // Estado de la reserva
            $table->timestamps(); // Marcas de tiempo (created_at, updated_at)
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
