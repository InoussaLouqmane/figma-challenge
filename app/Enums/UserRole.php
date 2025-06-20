<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Jury = 'jury';
    case Challenger = 'challenger';
}
