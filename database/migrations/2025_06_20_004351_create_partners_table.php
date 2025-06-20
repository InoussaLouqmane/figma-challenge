<?php

use App\Enums\PartnerType;
use App\Models\Partner;
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
        Schema::create(Partner::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->string(Partner::COL_NAME);
            $table->string(Partner::COL_LOGO);
            $table->text(Partner::COL_DESCRIPTION)->nullable();
            $table->enum(Partner::COL_TYPE, array_column(PartnerType::cases(), 'value'));
            $table->string(Partner::COL_WEBSITE)->nullable();
            $table->boolean(Partner::COL_VISIBLE)->default(true);
            $table->integer(Partner::COL_POSITION)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
