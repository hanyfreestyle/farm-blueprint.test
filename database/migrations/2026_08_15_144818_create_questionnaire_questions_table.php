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
        Schema::create('questionnaire_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')
                ->constrained('questionnaire_sections')
                ->restrictOnDelete();
            $table->string('title');
            $table->text('help_text')->nullable();
            $table->string('type');
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->foreignId('depends_on_question_id')
                ->nullable()
                ->constrained('questionnaire_questions')
                ->nullOnDelete();
            $table->string('dependency_operator')->nullable();
            $table->string('dependency_value')->nullable();
            $table->string('report_category')->nullable();
            $table->string('target_entity')->nullable();
            $table->timestamps();

            $table->index(['section_id', 'sort_order']);
            $table->index('depends_on_question_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_questions');
    }
};
