<?php

use App\Enums\ProjectStatus;
use App\Models\Project;
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

        Schema::create(Project::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->foreignId(Project::COL_CHALLENGE_ID)->constrained()->onDelete('cascade');
            $table->string(Project::COL_TITLE);
            $table->text(Project::COL_DESCRIPTION)->nullable();
            $table->text(Project::COL_OBJECTIVE)->nullable();
            $table->string(Project::COL_COVER_URL)->nullable();
            $table->string(Project::COL_COVER_ID)->nullable();
            $table->string(Project::COL_CATEGORY);
            $table->date(Project::COL_START_DATE)->nullable();
            $table->date(Project::COL_DEADLINE);
            $table->enum(Project::COL_STATUS, array_column(ProjectStatus::cases(), 'value'))->default(ProjectStatus::Active->value);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Soumission::TABLENAME);
        Schema::dropIfExists(Project::TABLENAME);
    }
};
