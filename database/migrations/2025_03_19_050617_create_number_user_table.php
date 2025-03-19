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
        Schema::create('number_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Number::class)->index()->constrained();
            $table->foreignIdFor(\App\Models\User::class)->index()->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('number_user');
    }
};
