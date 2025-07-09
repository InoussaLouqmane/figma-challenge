<?php


namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Table
    public const TABLENAME = 'users';

    // Colonnes
    public const COL_ID                = 'id';
    public const COL_NAME              = 'name';
    public const COL_EMAIL             = 'email';
    public const COL_PASSWORD          = 'password';
    public const COL_ROLE              = 'role';
    public const COL_COUNTRY           = 'country';
    public const COL_PHONE             = 'phone';
    public const COL_BIO               = 'bio';
    public const COL_PHOTO_ID             = 'photo_id';
    public const COL_PHOTO_URL = 'photo_url';

    public const COL_STATUS            = 'status';
    public const COL_EMAIL_VERIFIED_AT= 'email_verified_at';
    public const COL_REMEMBER_TOKEN    = 'remember_token';
    public const COL_CREATED_AT        = 'created_at';
    public const COL_UPDATED_AT        = 'updated_at';

    protected $appends=['isRegistered', 'registeredProjects'];
    protected $fillable = [
        self::COL_NAME,
        self::COL_EMAIL,
        self::COL_PASSWORD,
        self::COL_ROLE,
        self::COL_COUNTRY,
        self::COL_PHONE,
        self::COL_BIO,
        self::COL_PHOTO_ID,
        self::COL_STATUS,
        self::COL_PHOTO_URL,
        self::COL_STATUS
    ];

    protected $casts = [
        self::COL_ROLE => UserRole::class,
        self::COL_STATUS => UserStatus::class,
        self::COL_EMAIL_VERIFIED_AT => 'datetime',
    ];

    protected $hidden = [
        self::COL_PASSWORD,
        self::COL_REMEMBER_TOKEN,
    ];

    // Relations

    public function soumissions(): hasMany
    {
        return $this->hasMany(Soumission::class);
    }

    public function subscriptions(): BelongsToMany
    {
      return $this->belongsToMany(
          Project::class,
          Soumission::TABLENAME,
          Soumission::COL_USER_ID,
          Soumission::COL_PROJECT_ID
      )->withPivot([
          Soumission::COL_CHALLENGE_ID,
          Soumission::COL_STATUS,
          Soumission::COL_SOUMISSION_DATE,
          Soumission::COL_FIGMA_LINK

      ])->withTimestamps();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(NoteJury::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'uploaded_by');
    }


    public function getIsRegisteredAttribute(): bool
    {
        return $this->soumissions()->exists();
    }

    public function IsRegisteredToAProject(): HasMany
    {
        return $this->hasMany(Soumission::class, Soumission::COL_USER_ID);
    }
    public function notifications()
    {
        return $this->belongsToMany(
            Notification::class,
        NotificationUser::TABLENAME,
        NotificationUser::USER_ID,
        NotificationUser::NOTIFICATION_ID
        )->withPivot([
            NotificationUser::READ_AT
        ])->withTimestamps();
    }

    public function noteSoumissions(): HasMany{
        return  $this->belongsToMany(
            User::class,
            NoteJury::TABLENAME,
            NoteJury::COL_USER_ID,
            NoteJury::COL_SOUMISSION_ID,

        )->withPivot(
            NoteJury::COL_GRAPHISME,
            NoteJury::COL_ANIMATION,
            NoteJury::COL_NAVIGATION,
            NoteJury::COL_COMMENTAIRE,

        )->withTimestamps();
    }

    public function registrationInfos(){
        return $this->hasOne(RegistrationInfos::class, RegistrationInfos::USER_ID, User::COL_ID);
    }

    public function getRegisteredProjectsAttribute()
    {
        return $this->soumissions()
            ->with('project') // charge les détails du projet
            ->get()
            ->map(function ($soumission) {
                return [
                    'project' => $soumission->project, // relation project à ajouter ci-dessous
                    'figma_link' => $soumission->figma_link,
                    'soumission_date' => $soumission->soumission_date,
                    'status' => $soumission->status,
                ];
            });
    }
}
