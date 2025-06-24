<?php

namespace App\Models;

use App\Enums\ChallengeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Challenge extends Model
{
    use HasFactory, HasApiTokens;

    public const TABLENAME = 'challenges';

    public const COL_ID = 'id';
    public const COL_TITLE = 'title';
    public const COL_DESCRIPTION = 'description';
    public const COL_COVER = 'cover';
    public const COL_STATUS = 'status';
    public const COL_END_DATE = 'end_date';
    public const COL_CREATED_AT = 'created_at';
    public const COL_UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::COL_TITLE,
        self::COL_DESCRIPTION,
        self::COL_COVER,
        self::COL_STATUS,
        self::COL_END_DATE,
    ];

    protected $casts = [
        self::COL_STATUS => ChallengeStatus::class,
        self::COL_END_DATE => 'date',
    ];

    public function projects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Project::class);
    }
}
