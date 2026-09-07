<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_levels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->unsignedInteger('level');
            $table->string('name', 150);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique('level');
            $table->unique('role_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_levels');
    }
};