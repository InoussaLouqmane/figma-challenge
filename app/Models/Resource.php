<?php

namespace App\Models;

use App\Enums\ResourceCategory;
use App\Enums\ResourceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    public const TABLENAME = 'resources';

    public const COL_ID = 'id';
    public const COL_TITLE = 'title';
    public const COL_DESCRIPTION = 'description';
    public const COL_LINK = 'link';
    public const COL_TYPE = 'type';
    public const COL_CATEGORY = 'category';
    public const COL_VISIBLE_AT = 'visible_at';
    public const COL_UPLOADED_BY = 'uploaded_by';
    public const COL_CREATED_AT = 'created_at';
    public const COL_UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::COL_TITLE,
        self::COL_DESCRIPTION,
        self::COL_LINK,
        self::COL_TYPE,
        self::COL_CATEGORY,
        self::COL_VISIBLE_AT,
        self::COL_UPLOADED_BY,
    ];

    protected $casts = [
        self::COL_TYPE => ResourceType::class,
        self::COL_CATEGORY => ResourceCategory::class,
        self::COL_VISIBLE_AT => 'datetime',
    ];

    public function uploader(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
