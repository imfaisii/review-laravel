<?php

namespace App\Actions\Common;

use App\Traits\Common\HasApiResource;
use App\Traits\Common\NewQueryTrait;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AbstractUpdateAction
{
    use NewQueryTrait;
    use HasApiResource;

    /**
     * @param BaseModel|Role|Permission $model
     * @param array $data
     * @return BaseModel|Role|Permission
     */
    public function update(BaseModel|Role|Permission $model, array $data): BaseModel|Role|Permission
    {
        return tap($model, function (BaseModel|Role|Permission $model) use ($data) {
            $model->update($data);
        });
    }
}
