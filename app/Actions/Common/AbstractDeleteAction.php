<?php

namespace App\Actions\Common;

use App\Traits\Common\NewQueryTrait;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class AbstractDeleteAction
{
    use NewQueryTrait;

    /**
     * @param BaseModel $model
     * @return bool|null
     */
    public function delete(BaseModel|Role|Permission $model): ?bool
    {
        return $model->delete();
    }

    /**
     * @param  BaseModel  $model
     * @return bool|null
     */
    public function force(BaseModel $model): ?bool
    {
        return $model->forceDelete();
    }
}
