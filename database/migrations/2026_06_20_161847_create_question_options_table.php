<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id')->nullable();
            $table->text('option_text')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('is_correct')->default(0)->comment('0=no, 1=yes');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
