<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('brandings', function (Blueprint $table) {
            $table->string('default_language')->default('en');
            $table->json('accepted_languages')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('brandings', function (Blueprint $table) {
            $table->dropColumn(['default_language', 'accepted_languages']);
        });
    }
};