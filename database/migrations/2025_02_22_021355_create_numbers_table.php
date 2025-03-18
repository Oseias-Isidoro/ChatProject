<?php

use App\Enums\StatusEnum;
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
        Schema::create('numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Account::class)->nullable()->index();
            $table->string('name', 100);
            $table->string('phone_number', 12);
            $table->enum('status', StatusEnum::all())->default(StatusEnum::INACTIVE->name);
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['account_id', 'phone_number', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numbers');
    }
};
