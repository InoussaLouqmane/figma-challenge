<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    public const TABLENAME = 'site_settings';

    public const COL_ID = 'id';
    public const COL_SITE_NAME = 'site_name';
    public const COL_ABOUT = 'about';
    public const COL_EMAIL = 'email';
    public const COL_PHONE = 'phone';
    public const COL_LOGO = 'logo';
    public const COL_FACEBOOK = 'facebook';
    public const COL_LINKEDIN = 'linkedin';
    public const COL_GITHUB = 'github';

    protected $fillable = [
        self::COL_SITE_NAME,
        self::COL_ABOUT,
        self::COL_EMAIL,
        self::COL_PHONE,
        self::COL_LOGO,
        self::COL_FACEBOOK,
        self::COL_LINKEDIN,
        self::COL_GITHUB,
    ];
}
