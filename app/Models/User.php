<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Actions\Common\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function App\Helpers\get_permissions_as_modules_array;

class User extends BaseModel implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, HasFactory, Notifiable, Authenticatable, Authorizable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    protected array $allowedAppends = [];

    protected array $allowedIncludes = [];

    protected $appends = ['rights', 'top_role'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getRightsAttribute()
    {
        return $this->getPermissions();
    }

    public function getTopRoleAttribute()
    {
        return Str::ucfirst(Str::replace("_", " ", Arr::first($this->getRoleNames())));
    }

    public function getPermissions()
    {
        $permissions = $this->getAllPermissions();


        return [
            'roles' => $this->getRoleNames(),
            'permissions' => get_permissions_as_modules_array($permissions),
        ];
    }
}
