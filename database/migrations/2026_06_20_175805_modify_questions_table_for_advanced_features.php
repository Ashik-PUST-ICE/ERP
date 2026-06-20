<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('board_id')->nullable()->after('difficulty');
            $table->unsignedBigInteger('stem_id')->nullable()->after('board_id');
            $table->json('options_json')->nullable()->after('explanation');
            $table->dropColumn('board');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('board')->nullable();
            $table->dropColumn(['board_id', 'stem_id', 'options_json']);
        });
    }
};
