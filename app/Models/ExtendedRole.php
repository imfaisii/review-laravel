<?php

namespace App\Models;

use App\Actions\Common\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;
use Spatie\Permission\PermissionRegistrar;

use function App\Helpers\get_permissions_as_modules_array;
use function App\Helpers\is_include_present;

class ExtendedRole extends BaseModel implements Role
{
    protected $table = 'roles';

    protected array $allowedIncludes = ['permissions'];

    protected $appends = ['formatted_permissions', 'formatted_name', 'users'];

    public function getFormattedPermissionsAttribute(): array
    {
        //! TO AVOID N+1
        if (!is_include_present('permissions')) {
            return [];
        }

        return get_permissions_as_modules_array($this->permissions);
    }

    public function getFormattedNameAttribute(): string
    {
        return Str::ucfirst(Str::replace("_", " ", $this->name));
    }

    public function getUsersAttribute(): mixed
    {
        return User::role($this->name)->get();
    }

    public static function findById(int $id, $guardName): self
    {
        return ModelsRole::where('id', $id)->first();
    }

    public static function findByName(string $name, $guardName): self
    {
        return ModelsRole::where('name', $name)->first();
    }

    public static function findOrCreate(string $name, $guard): self
    {
        return ModelsRole::firstOrCreate(['name' => $name]);
    }

    public function hasPermissionTo($permission): bool
    {
        return true;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotRole,
            PermissionRegistrar::$pivotPermission
        );
    }
}
