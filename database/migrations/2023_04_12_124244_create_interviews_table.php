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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_master_id');
            $table->unsignedBigInteger('interview_type_id');
            $table->unsignedBigInteger('interviewer_id');
            $table->unsignedBigInteger('interview_mode_id');
            $table->timestamp('date')->nullable();
            $table->string('remarks')->nullable();
            $table->string('location_link')->nullable();
            $table->integer('total_rating')->default(0)->nullable();
            $table->string('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
