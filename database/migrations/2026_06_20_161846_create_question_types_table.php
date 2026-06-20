<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('has_options')->default(0)->comment('0=no, 1=yes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_types');
    }
};
