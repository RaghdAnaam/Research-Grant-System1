<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academician_grant', function (Blueprint $table) {
            $table->foreignId('academician_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grant_id')->constrained()->cascadeOnDelete();
            $table->primary(['academician_id', 'grant_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academician_grant');
    }
};
