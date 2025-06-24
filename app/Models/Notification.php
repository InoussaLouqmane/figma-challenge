<?php

namespace App\Models;

use App\Enums\NotificationAudience;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    use HasFactory;

    public const TABLENAME = 'notifications';

    public const COL_ID = 'id';
    public const COL_TITLE = 'title';
    public const COL_CONTENT = 'content';
    public const COL_AUDIENCE = 'audience';
    public const COL_SCHEDULED_AT = 'scheduled_at';
    public const COL_CREATED_AT = 'created_at';
    public const COL_UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::COL_TITLE,
        self::COL_CONTENT,
        self::COL_AUDIENCE,
        self::COL_SCHEDULED_AT,
    ];

    protected $casts = [
        self::COL_AUDIENCE => NotificationAudience::class,
        self::COL_SCHEDULED_AT => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,
                    NotificationUser::TABLENAME,
            NotificationUser::NOTIFICATION_ID,
            NotificationUser::USER_ID)
            ->withPivot('read_at')
            ->withTimestamps();
    }
}
