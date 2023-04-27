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
        Schema::create('candidate_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_master_id');
            $table->unsignedBigInteger('skill_master_id');
            $table->double('experience')->default(0)->nullable();
            $table->integer('self_rating')->default(0)->nullable();
            $table->integer('theory_rating')->default(0)->nullable();
            $table->integer('practical_rating')->default(0)->nullable();
    

            // $table->foreign('candidate_master_id')->references('id')->on('candidate_masters')->onDelete('cascade');
            // $table->foreign('skill_master_id')->references('id')->on('skill_masters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_skills');
    }
};
