<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public const TABLENAME = 'projects';

    public const COL_ID = 'id';
    public const COL_CHALLENGE_ID = 'challenge_id';
    public const COL_TITLE = 'title';
    public const COL_DESCRIPTION = 'description';
    public const COL_COVER = 'cover';
    public const COL_CATEGORY = 'category';
    public const COL_START_DATE = 'start_date';
    public const COL_DEADLINE = 'deadline';
    public const COL_STATUS = 'status';
    public const COL_CREATED_AT = 'created_at';
    public const COL_UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::COL_CHALLENGE_ID,
        self::COL_TITLE,
        self::COL_DESCRIPTION,
        self::COL_COVER,
        self::COL_CATEGORY,
        self::COL_START_DATE,
        self::COL_DEADLINE,
        self::COL_STATUS,
    ];

    protected $casts = [
        self::COL_STATUS => ProjectStatus::class,
        self::COL_START_DATE => 'date',
        self::COL_DEADLINE => 'date',
    ];

    public function challenge(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function soumissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Soumission::class);
    }
}
