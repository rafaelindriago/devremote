<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('developers', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name', 50);
            $table->string('last_name', 50);
            $table->date('birth_at');
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->unique();
            $table->string('country_code', 3);
            $table->foreign('country_code')->references('code')->on('countries');
            $table->foreignUuid('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
