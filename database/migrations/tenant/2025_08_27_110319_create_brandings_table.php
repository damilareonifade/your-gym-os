<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('brandings')) {
            Schema::create('brandings', function (Blueprint $table) {
                $table->ulid('id')->primary();
                $table->json('colors')->nullable();
                $table->string('brand_logo')->nullable();
                $table->string("dark_mode_logo")->nullable();
                $table->string('favicon')->nullable();
                $table->string('facebook_social_account')->nullable();
                $table->string('instagram_social_account')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brandings');
    }
};
