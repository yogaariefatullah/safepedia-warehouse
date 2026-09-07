<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_requests', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50)->unique();

            $table->foreignId('requestor_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('warehouse_name', 200);
            $table->text('address');

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->decimal('area', 15, 2);

            $table->decimal('estimated_budget', 18, 2);

            $table->text('description');

            $table->string('status', 30)->default('draft');

            $table->unsignedInteger('current_approval_level')->nullable();

            $table->text('requestor_note')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->index('requestor_id');
            $table->index('status');
            $table->index('current_approval_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_requests');
    }
};
