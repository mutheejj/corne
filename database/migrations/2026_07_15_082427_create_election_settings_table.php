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
        Schema::create('election_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->boolean('allow_abstain')->default(true);
            $table->boolean('show_results_live')->default(false);
            $table->boolean('show_vote_count')->default(true);
            $table->boolean('require_student_id_verification')->default(true);
            $table->integer('max_votes_per_position')->default(1);
            $table->integer('voting_time_limit_minutes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('election_settings');
    }
};
