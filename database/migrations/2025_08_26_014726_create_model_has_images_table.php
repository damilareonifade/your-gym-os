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
        Schema::create('model_has_images', function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->text("path")->nullable();
            $table->json("icon")->nullable();
            $table->json("tag")->nullable();
            $table->boolean("has_icon")->default(false);
            $table->boolean("has_image")->default(false);
            $table->nullableUlidMorphs('imageable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_images');
    }
};
