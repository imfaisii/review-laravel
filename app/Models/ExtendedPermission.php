<?php

namespace App\Models;

use App\Actions\Common\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Spatie\Permission\Models\Role;

class ExtendedPermission extends BaseModel implements Permission
{
    protected $table = 'permissions';

    public static function findById(int $id, $guardName): self
    {
        return ModelsPermission::where('id', $id)->first();
    }

    public static function findByName(string $name, $guardName): self
    {
        return ModelsPermission::where('name', $name)->first();
    }

    public static function findOrCreate(string $name, $guard): self
    {
        return ModelsPermission::firstOrCreate(['name' => $name]);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
