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
        Schema::create('questionnaire_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                ->constrained('questionnaire_questions')
                ->cascadeOnDelete();
            $table->json('value')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('needs_review')->default(false);
            $table->string('review_status')->default('pending');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique('question_id');
            $table->index(['needs_review', 'review_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_answers');
    }
};
