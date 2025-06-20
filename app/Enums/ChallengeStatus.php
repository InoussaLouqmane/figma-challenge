<?php

namespace App\Enums;

enum ChallengeStatus: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Closed = 'closed';
}
