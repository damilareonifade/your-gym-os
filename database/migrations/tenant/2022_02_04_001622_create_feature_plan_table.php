<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_plan', function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->decimal('charges')->nullable();
            $table->foreignUlid('feature_id')->constrained('features')->cascadeOnDelete()->name("feature_plan_feature_foreign");
            $table->foreignUlid('plan_id')->constrained('plans')->cascadeOnDelete()->name("feature_plan_plan_foreign");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_plan');
    }
};
