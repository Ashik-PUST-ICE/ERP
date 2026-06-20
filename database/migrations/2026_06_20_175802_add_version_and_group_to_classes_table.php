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
        Schema::table('classes', function (Blueprint $table) {
            $table->tinyInteger('version')->nullable()->comment('1=Bangla, 2=English');
            $table->tinyInteger('group')->nullable()->comment('1=General, 2=Science, 3=Humanities, 4=Business');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['version', 'group']);
        });
    }
};
