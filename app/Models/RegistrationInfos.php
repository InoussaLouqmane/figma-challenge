<?php

namespace App\Models;

use App\Enums\FigmaSkills;
use App\Enums\UXSkills;
use Illuminate\Database\Eloquent\Model;

class RegistrationInfos extends Model
{
    protected $table = 'registration_infos';

    const TABLENAME = 'registration_infos';
    const ID = 'id';
    const USER_ID = 'user_id';
    const FigmaSkills = 'figmaSkills';
    const UXSkills = 'uxSkills';
    const Objective = 'objective';
    const AcquisitionChannel = 'acquisitionChannel';
    const LinkToPortfolio = 'linkToPortfolio';
    const FirstAttempt = 'firstAttempt';
    const isActive = 'isActive';


    protected $fillable = [
        self::USER_ID,
        self::FigmaSkills,
        self::UXSkills,
        self::Objective,
        self::AcquisitionChannel,
        self::LinkToPortfolio,
        self::FirstAttempt,
    ];
    protected $cast = [
        self::FigmaSkills => FigmaSkills::class,
        self::UXSkills => UXSkills::class,
        self::LinkToPortfolio => 'url',
    ];

    public function user(){
        return $this->belongsTo(User::class, self::USER_ID, self::ID);
    }

}
