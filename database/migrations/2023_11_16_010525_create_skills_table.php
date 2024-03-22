<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->enum('level', ['Beginner', 'Intermediate', 'Expert']);
            $table->text('description');
            $table->foreignUuid('developer_id')->constrained();
            $table->foreignUuid('language_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
