<?php

namespace App\Models;

use App\Enums\PartnerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    public const TABLENAME = 'partners';

    public const COL_ID = 'id';
    public const COL_NAME = 'name';
    public const COL_LOGO = 'logo';
    public const COL_CHALLENGE_ID='challenge_id';
    public const COL_DESCRIPTION = 'description';
    public const COL_TYPE = 'type';
    public const COL_WEBSITE = 'website';
    public const COL_VISIBLE = 'visible';
    public const COL_POSITION = 'position';
    public const COL_CREATED_AT = 'created_at';
    public const COL_UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::COL_CHALLENGE_ID,
        self::COL_NAME,
        self::COL_LOGO,
        self::COL_DESCRIPTION,
        self::COL_TYPE,
        self::COL_WEBSITE,
        self::COL_VISIBLE,
        self::COL_POSITION,
    ];

    protected $casts = [
        self::COL_VISIBLE => 'boolean',
        self::COL_TYPE => PartnerType::class,
    ];

    public function edition(){
        $this->belongsTo(Challenge::class);
    }
}
