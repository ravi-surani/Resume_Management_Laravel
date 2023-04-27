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
        Schema::create('skill_masters', function (Blueprint $table) {
            $table->id();
            $table->string('skill');
            $table->unsignedBigInteger('skill_type_id');
            $table->boolean('status')->default(true);

            $table->foreign('skill_type_id')->references('id')->on('skill_type_masters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_masters');
    }
};
