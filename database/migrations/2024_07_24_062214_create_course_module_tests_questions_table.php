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
        Schema::create('course_module_test_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_module_test_id');
            $table->foreign('course_module_test_id')->on('course_module_tests')->references('id')->onDelete('cascade');
            $table->text('question');
            $table->tinyInteger('status')->default(1)->comment("1->active,2->inactive");
            $table->tinyInteger('answer')->nullable();
            $table->string('option_1');
            $table->string('option_2');
            $table->string('option_3');
            $table->string('option_4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_module_test_questions');
    }
};
