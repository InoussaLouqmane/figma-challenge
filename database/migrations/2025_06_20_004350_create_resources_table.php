<?php

use App\Enums\ResourceCategory;
use App\Enums\ResourceType;
use App\Models\Resource;
use App\Models\User;
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
        Schema::create(Resource::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->string(Resource::COL_TITLE);
            $table->text(Resource::COL_DESCRIPTION)->nullable();
            $table->string(Resource::COL_LINK)->nullable();
            $table->enum(Resource::COL_TYPE, array_column(ResourceType::cases(), 'value'));
            $table->enum(Resource::COL_CATEGORY, array_column(ResourceCategory::cases(), 'value'));
            $table->timestamp(Resource::COL_VISIBLE_AT)->nullable();
            $table->foreignId(Resource::COL_UPLOADED_BY)
                ->nullable()
                ->constrained(User::TABLENAME)
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
