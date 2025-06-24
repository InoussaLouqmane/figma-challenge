<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    protected $table = 'notification_user';
    public const TABLENAME = 'notification_user';
    public const ID = 'id';
    public const NOTIFICATION_ID = 'notification_id';
    public const USER_ID = 'user_id';
    public const READ_AT = 'read_at';

    protected $fillable=[
        self::NOTIFICATION_ID,
        self::USER_ID,
    ];

    protected $casts = [];

    public function notification(){
        return $this->belongsTo(Notification::class,'notification_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
