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
        Schema::create('brandings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('color')->nullable();
            $table->string('brand_logo')->nullable();
            $table->string('facebook_social_account')->nullable(true);
            $table->string('instagram_social_account')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brandings');
    }
};
