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
        Schema::create('grants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leader_id')->constrained('academicians')->onDelete('cascade');
            $table->string('grant_provider');
            $table->string('project_title');
            $table->decimal('grant_amount', 10, 2);
            $table->date('start_date');
            $table->integer('duration_months');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grants');
    }
};
