<?php

use App\Models\ContactMessage;
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
        Schema::create(ContactMessage::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->string(ContactMessage::COL_NAME);
            $table->string(ContactMessage::COL_EMAIL);
            $table->text(ContactMessage::COL_MESSAGE);
            $table->timestamp(ContactMessage::COL_READ_AT)->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
