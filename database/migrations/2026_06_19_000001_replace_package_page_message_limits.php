<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'max_questions')) {
                $table->integer('max_questions')->nullable()->comment('null = unlimited')->after('slug');
            }
            if (!Schema::hasColumn('packages', 'max_teachers')) {
                $table->integer('max_teachers')->nullable()->comment('null = unlimited')->after('max_questions');
            }
            if (!Schema::hasColumn('packages', 'max_question_sets')) {
                $table->integer('max_question_sets')->nullable()->comment('null = unlimited')->after('max_teachers');
            }
            if (Schema::hasColumn('packages', 'page_limit')) {
                $table->dropColumn('page_limit');
            }
            if (Schema::hasColumn('packages', 'message_limit')) {
                $table->dropColumn('message_limit');
            }
        });

        Schema::table('user_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('user_packages', 'max_questions')) {
                $table->integer('max_questions')->nullable()->comment('null = unlimited')->after('name');
            }
            if (!Schema::hasColumn('user_packages', 'max_teachers')) {
                $table->integer('max_teachers')->nullable()->comment('null = unlimited')->after('max_questions');
            }
            if (!Schema::hasColumn('user_packages', 'max_question_sets')) {
                $table->integer('max_question_sets')->nullable()->comment('null = unlimited')->after('max_teachers');
            }
            if (Schema::hasColumn('user_packages', 'page_limit')) {
                $table->dropColumn('page_limit');
            }
            if (Schema::hasColumn('user_packages', 'message_limit')) {
                $table->dropColumn('message_limit');
            }
        });
    }

    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'page_limit')) {
                $table->integer('page_limit')->nullable()->default(0)->after('slug');
            }
            if (!Schema::hasColumn('packages', 'message_limit')) {
                $table->integer('message_limit')->nullable()->default(0)->after('page_limit');
            }
            if (Schema::hasColumn('packages', 'max_questions')) {
                $table->dropColumn('max_questions');
            }
            if (Schema::hasColumn('packages', 'max_teachers')) {
                $table->dropColumn('max_teachers');
            }
            if (Schema::hasColumn('packages', 'max_question_sets')) {
                $table->dropColumn('max_question_sets');
            }
        });

        Schema::table('user_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('user_packages', 'page_limit')) {
                $table->integer('page_limit')->default(0)->after('name');
            }
            if (!Schema::hasColumn('user_packages', 'message_limit')) {
                $table->integer('message_limit')->default(0)->after('page_limit');
            }
            if (Schema::hasColumn('user_packages', 'max_questions')) {
                $table->dropColumn('max_questions');
            }
            if (Schema::hasColumn('user_packages', 'max_teachers')) {
                $table->dropColumn('max_teachers');
            }
            if (Schema::hasColumn('user_packages', 'max_question_sets')) {
                $table->dropColumn('max_question_sets');
            }
        });
    }
};
