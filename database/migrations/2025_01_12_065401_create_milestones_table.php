<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained('grants')->onDelete('cascade');
            $table->string('milestone_name');
            $table->date('target_completion_date');
            $table->string('deliverable');
            $table->enum('status', ['Pending', 'Completed'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
