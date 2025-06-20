<?php

namespace App\Enums;

enum SoumissionStatus: string
{
    case EnAttente = 'en_attente';
    case Soumis = 'soumis';
    case HorsDelai = 'hors_delai';
}
