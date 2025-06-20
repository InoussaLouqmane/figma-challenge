<?php

use App\Enums\NotificationAudience;
use App\Models\Notification;
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
        Schema::create(Notification::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->string(Notification::COL_TITLE);
            $table->text(Notification::COL_CONTENT);
            $table->enum(Notification::COL_AUDIENCE, array_column(NotificationAudience::cases(), 'value'))->default(NotificationAudience::All->value);
            $table->timestamp(Notification::COL_SCHEDULED_AT)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
