<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('citas2s', function (Blueprint $table) {
            $table->id();
            $table->integer('orden');
            $table->string('descripcion');
            $table->integer('status');
            $table->date('FechaProgramada');
            $table->date('FechaRealizada');
            $table->string('RFC');
            $table->string('Nombre');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas2s');
    }
};
