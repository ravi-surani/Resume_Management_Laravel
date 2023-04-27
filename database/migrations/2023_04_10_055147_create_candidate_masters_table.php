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
        Schema::create('candidate_masters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('contect_no');
            $table->date('dob')->nullable();
            $table->unsignedBigInteger('mode_of_work_id')->nullable();
            $table->unsignedBigInteger('degree_id')->nullable();
            $table->string('passing_year')->nullable();
            $table->string('passing_grade')->nullable();
            $table->string('total_experience')->nullable();
            $table->string('current_salary')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('is_negotiable')->nullable();
            $table->string('notice_period')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('countary')->nullable();
            $table->string('resume_id')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('recruitment_status_id');
            $table->unsignedBigInteger('source_id');
            // $table->foreign('mode_of_work_id')->references('id')->on('mode_of_work_masters')->onDelete('cascade');
            // $table->foreign('degree_id')->references('id')->on('degree_masters')->onDelete('cascade');
            // $table->foreign('current_id')->references('id')->on('recruitment_status_masters')->onDelete('cascade');
            // $table->foreign('source_id')->references('id')->on('source_masters')->onDelete('cascade');\

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_masters');
    }
};
