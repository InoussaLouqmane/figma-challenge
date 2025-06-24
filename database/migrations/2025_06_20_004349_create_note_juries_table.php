<?php

use App\Models\NoteJury;
use App\Models\Soumission;
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
        Schema::create(NoteJury::TABLENAME, function (Blueprint $table) {
            $table->id();
            $table->foreignId(NoteJury::COL_USER_ID)->constrained(User::TABLENAME)->onDelete('cascade'); // jury
            $table->foreignId(NoteJury::COL_SOUMISSION_ID)->constrained(Soumission::TABLENAME)->onDelete('cascade');
            $table->unsignedTinyInteger(NoteJury::COL_GRAPHISME)->default(0);
            $table->unsignedTinyInteger(NoteJury::COL_ANIMATION)->default(0);
            $table->unsignedTinyInteger(NoteJury::COL_NAVIGATION)->default(0);
            $table->text(NoteJury::COL_COMMENTAIRE)->nullable();
            $table->timestamps();
            $table->unique([NoteJury::COL_USER_ID, NoteJury::COL_SOUMISSION_ID]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_juries');
    }
};
