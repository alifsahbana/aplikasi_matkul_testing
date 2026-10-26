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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Nama kategori rantai pasok tembakau');
            $table->string('slug', 100)->unique();
            $table->string('marker_color', 20)->default('#15803d')->comment('Kode heksadesimal warna marker peta');
            $table->string('icon', 50)->default('leaf')->comment('Identifikasi ikon');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
