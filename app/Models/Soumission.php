<?php

namespace App\Models;

use App\Enums\SoumissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Soumission extends Pivot
{
    use HasFactory;

    public const TABLENAME = 'soumissions';

    public const COL_ID = 'id';
    public const COL_USER_ID = 'user_id';
    public const COL_PROJECT_ID = 'project_id';
    public const COL_CHALLENGE_ID = 'challenge_id';
    public const COL_INSCRIPTION_DATE = 'inscription_date';
    public const COL_FIGMA_LINK = 'figma_link';
    public const COL_SOUMISSION_DATE = 'soumission_date';
    public const COL_COMMENTAIRE = 'commentaire';
    public const COL_STATUS = 'status';
    public const COL_CREATED_AT = 'created_at';
    public const COL_UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::COL_USER_ID,
        self::COL_PROJECT_ID,
        self::COL_CHALLENGE_ID,
        self::COL_INSCRIPTION_DATE,
        self::COL_FIGMA_LINK,
        self::COL_SOUMISSION_DATE,
        self::COL_COMMENTAIRE,
        self::COL_STATUS,
    ];

    protected $casts = [
        self::COL_INSCRIPTION_DATE => 'datetime',
        self::COL_SOUMISSION_DATE => 'datetime',
        self::COL_STATUS => SoumissionStatus::class,
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function challenge(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NoteJury::class);
    }
}
