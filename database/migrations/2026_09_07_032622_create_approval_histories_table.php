<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('warehouse_request_id')
                ->constrained('warehouse_requests')
                ->cascadeOnDelete();

            $table->foreignId('approval_level_id')
                ->constrained('approval_levels')
                ->restrictOnDelete();

            $table->foreignId('approver_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('status', 30);

            $table->text('note')->nullable();

            $table->timestamp('action_at')->useCurrent();

            $table->timestamps();

            $table->index('warehouse_request_id');
            $table->index('approval_level_id');
            $table->index('approver_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_histories');
    }
};
