<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Track how question was created
        Schema::table('questions', function (Blueprint $table) {
            $table->string('source_type')->nullable()->after('source')
                ->comment('manual, excel_import, ai_generated');
        });

        // Book/File library for AI generation
        Schema::create('uploaded_books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type')->comment('pdf, docx, xlsx, txt');
            $table->longText('extracted_text')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=active, 2=inactive');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('source_type');
        });
        Schema::dropIfExists('uploaded_books');
    }
};
