<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('question_type_id')->nullable();
            $table->text('question_text');
            $table->string('image')->nullable();
            $table->text('correct_answer')->nullable();
            $table->text('explanation')->nullable();
            $table->decimal('marks', 8, 2)->default(1);
            $table->tinyInteger('difficulty')->default(2)->comment('1=easy, 2=medium, 3=hard');
            $table->string('board')->nullable();
            $table->string('year')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=draft, 2=published, 3=archived');
            $table->tinyInteger('source')->default(1)->comment('1=super_admin, 2=suggestion_approved, 3=textbook_pdf_generated, 4=question_paper_pdf_extracted');
            $table->unsignedBigInteger('book_upload_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
