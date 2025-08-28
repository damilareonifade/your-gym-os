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
        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->nullableUlidMorphs('model');
            $table->nullableUlidMorphs("owner");
            $table->string('name')->nullable();
            $table->string("description")->nullable();
            $table->string('type')->nullable();
            $table->string('tag')->nullable();
            $table->string('tag_type')->nullable();
            $table->string('link_type')->nullable();
            $table->string("web_link")->nullable();
            $table->string("mobile_link")->nullable();
            $table->string("navigation_menu_id")->nullable()->index();
            $table->boolean("ios")->default(false);
            $table->boolean("android")->default(false);
            $table->boolean("web")->default(false);
            $table->boolean("default_page")->default(false);
            $table->unsignedInteger('rank')->default(0);
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation_menus');
    }
};