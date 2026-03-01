<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')
                ->index();
            $table->string('action')
                ->index();
            $table->string('entity_type')
                ->nullable()
                ->index();
            $table->unsignedBigInteger('entity_id')
                ->nullable()
                ->index();
            $table->json('old_values')
                ->nullable();
            $table->json('new_values')
                ->nullable();
            $table->string('session_id')
                ->nullable()
                ->index();
            $table->string('ip_address_value')
                ->nullable();
            $table->timestamp('created_at')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
