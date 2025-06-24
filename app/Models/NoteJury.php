<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteJury extends Model
{

    use HasFactory;

    protected $table = NoteJury::TABLENAME;
    public const TABLENAME = 'note_juries';

    public const COL_ID = 'id';
    public const COL_USER_ID = 'jury_id';
    public const COL_SOUMISSION_ID = 'soumission_id';
    public const COL_GRAPHISME = 'graphisme';
    public const COL_ANIMATION = 'animation';
    public const COL_NAVIGATION = 'navigation';
    public const COL_COMMENTAIRE = 'commentaire';
    public const COL_CREATED_AT = 'created_at';
    public const COL_UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::COL_USER_ID,
        self::COL_SOUMISSION_ID,
        self::COL_GRAPHISME,
        self::COL_ANIMATION,
        self::COL_NAVIGATION,
        self::COL_COMMENTAIRE,
    ];

    public function jury(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, self::COL_USER_ID);
    }

    public function soumission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Soumission::class);
    }
}
