<?php

namespace App\Enums;

enum ResourceType: string
{
    case PDF = 'pdf';
    case Lien = 'lien';
    case Autre = 'autre';
}

