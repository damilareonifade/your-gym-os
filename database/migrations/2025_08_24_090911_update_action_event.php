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
        Schema::table('action_events', function (Blueprint $table) {
            $table->dropIndex(['batch_id', 'model_type', 'model_id']);
            $table->dropMorphs('actionable');
            $table->dropMorphs('target');
            $table->dropColumn('model_id');
        });
        Schema::table('action_events', function (Blueprint $table) {
            $table->ulidMorphs('actionable');
            $table->ulidMorphs('target');
            $table->ulid('model_id')->nullable();

            $table->index(['batch_id', 'model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('action_events', function (Blueprint $table) {
            $table->dropIndex(['batch_id', 'model_type', 'model_id']);
            $table->dropMorphs('actionable');
            $table->dropMorphs('target');
            $table->dropColumn('model_id');
        });
        Schema::table('action_events', function (Blueprint $table) {
            $table->morphs('actionable');
            $table->morphs('target');
            $table->uuid('model_id')->nullable();

            $table->index(['batch_id', 'model_type', 'model_id']);
        });
    }
};
