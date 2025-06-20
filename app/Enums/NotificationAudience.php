<?php

namespace App\Enums;

enum NotificationAudience: string
{
    case All = 'all';
    case Admin = 'admin';
    case Jury = 'jury';
    case Challenger = 'challenger';
}

