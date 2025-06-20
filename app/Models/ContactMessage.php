<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    public const TABLENAME = 'contact_messages';

    public const COL_ID = 'id';
    public const COL_NAME = 'name';
    public const COL_EMAIL = 'email';
    public const COL_MESSAGE = 'message';
    public const COL_READ_AT = 'read_at';
    public const COL_CREATED_AT = 'created_at';

    protected $fillable = [
        self::COL_NAME,
        self::COL_EMAIL,
        self::COL_MESSAGE,
        self::COL_READ_AT,
    ];

    protected $casts = [
        self::COL_READ_AT => 'datetime',
    ];
}
