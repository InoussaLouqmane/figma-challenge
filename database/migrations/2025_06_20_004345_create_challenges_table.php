<?php

use App\Enums\ChallengeStatus;
use App\Models\Challenge;
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
        Schema::create(Challenge::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->string(Challenge::COL_TITLE);
            $table->text(Challenge::COL_DESCRIPTION)->nullable();
            $table->string(Challenge::COL_COVER)->nullable();
            $table->enum(Challenge::COL_STATUS, array_column(ChallengeStatus::cases(), 'value'))->default(ChallengeStatus::Draft->value);
            $table->date(Challenge::COL_END_DATE)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Challenge::TABLENAME);
    }
};
