<?php

use App\Enums\FigmaSkills;
use App\Enums\UXSkills;
use App\Models\RegistrationInfos;
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
        Schema::create( RegistrationInfos::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->foreignId(RegistrationInfos::USER_ID)->constrained()->onDelete('cascade');
            $table->text(RegistrationInfos::Objective);
            $table->string(RegistrationInfos::AcquisitionChannel)->nullable();
            $table->string(RegistrationInfos::LinkToPortfolio);
            $table->boolean(RegistrationInfos::FirstAttempt)->default(true);
            $table->boolean(RegistrationInfos::isActive)->default(true);
            $table->enum(RegistrationInfos::FigmaSkills, array_column(FigmaSkills::cases(), 'value'));
            $table->enum(RegistrationInfos::UXSkills, array_column(UXSkills::cases(), 'value'))->nullable();
            $table->timestamps();
        }

        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(RegistrationInfos::TABLENAME);
    }
};
