<?php

use App\Enums\SoumissionStatus;
use App\Models\Soumission;
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

        Schema::create(Soumission::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->foreignId(Soumission::COL_USER_ID)->constrained()->onDelete('cascade');
            $table->foreignId(Soumission::COL_PROJECT_ID)->constrained()->onDelete('cascade');
            $table->foreignId(Soumission::COL_CHALLENGE_ID)->constrained()->onDelete('cascade');
            $table->timestamp(Soumission::COL_INSCRIPTION_DATE);
            $table->string(Soumission::COL_FIGMA_LINK)->nullable();
            $table->timestamp(Soumission::COL_SOUMISSION_DATE)->nullable();
            $table->text(Soumission::COL_COMMENTAIRE)->nullable();
            $table->enum(Soumission::COL_STATUS, array_column(SoumissionStatus::cases(), 'value'))->default(SoumissionStatus::EnAttente->value);
            $table->timestamps();
            $table->unique([Soumission::COL_USER_ID, Soumission::COL_PROJECT_ID]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soumission');
    }
};
