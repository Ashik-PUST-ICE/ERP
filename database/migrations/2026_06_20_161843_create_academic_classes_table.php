<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institution_id')->nullable()->comment('null if global/master class');
            $table->string('name');
            $table->integer('order')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1=active, 2=inactive');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
