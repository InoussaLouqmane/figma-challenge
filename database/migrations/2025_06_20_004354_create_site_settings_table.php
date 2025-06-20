<?php

use App\Models\SiteSetting;
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
        Schema::create(SiteSetting::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->string(SiteSetting::COL_SITE_NAME);
            $table->text(SiteSetting::COL_ABOUT);
            $table->string(SiteSetting::COL_EMAIL);
            $table->string(SiteSetting::COL_PHONE);
            $table->string(SiteSetting::COL_LOGO)->nullable();
            $table->string(SiteSetting::COL_FACEBOOK)->nullable();
            $table->string(SiteSetting::COL_LINKEDIN)->nullable();
            $table->string(SiteSetting::COL_GITHUB)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
