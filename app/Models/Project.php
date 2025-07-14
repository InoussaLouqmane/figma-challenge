<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Project extends Model
{
    use HasApiTokens, HasFactory;

    public const TABLENAME = 'projects';

    public const COL_ID = 'id';
    public const COL_CHALLENGE_ID = 'challenge_id';
    public const COL_TITLE = 'title';
    public const COL_DESCRIPTION = 'description';
    public const COL_COVER_URL = 'cover_url';
    public const COL_COVER_ID = 'cover_id';
    public const COL_CATEGORY = 'category';
    public const COL_START_DATE = 'start_date';
    public const COL_DEADLINE = 'deadline';
    public const COL_STATUS = 'status';
    public const COL_OBJECTIVE = 'objective';
    public const COL_CREATED_AT = 'created_at';
    public const COL_UPDATED_AT = 'updated_at';


    protected $fillable = [
        self::COL_CHALLENGE_ID,
        self::COL_TITLE,
        self::COL_DESCRIPTION,
        self::COL_COVER_URL,
        self::COL_COVER_ID,
        self::COL_CATEGORY,
        self::COL_START_DATE,
        self::COL_DEADLINE,
        self::COL_OBJECTIVE,
        self::COL_STATUS,
    ];

    protected $casts = [
        self::COL_STATUS => ProjectStatus::class,
        self::COL_START_DATE => 'date',
        self::COL_DEADLINE => 'datetime',
    ];

    public function challenge(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Challenge::class, self::COL_CHALLENGE_ID, self::COL_ID);
    }

    public function soumissions(): HasMany
    {
        return $this->hasMany(Soumission::class);
    }

    public function subscribers(): belongsToMany{
        return  $this->belongsToMany(
            User::class,
            Soumission::TABLENAME,
            Soumission::COL_PROJECT_ID,
            Soumission::COL_USER_ID
        )->withPivot([
            Soumission::COL_CHALLENGE_ID,
            Soumission::COL_STATUS,
            Soumission::COL_SOUMISSION_DATE,
            Soumission::COL_FIGMA_LINK,
        ])->withTimestamps();
    }
}
