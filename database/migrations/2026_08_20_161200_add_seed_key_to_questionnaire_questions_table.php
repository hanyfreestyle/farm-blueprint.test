<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questionnaire_questions', function (Blueprint $table) {
            $table->string('seed_key')
                ->nullable()
                ->after('section_id');

            $table->unique(['section_id', 'seed_key']);
        });
    }

    public function down(): void
    {
        Schema::table('questionnaire_questions', function (Blueprint $table) {
            $table->dropUnique(['section_id', 'seed_key']);
            $table->dropColumn('seed_key');
        });
    }
};
