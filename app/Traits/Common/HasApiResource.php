<?php

namespace App\Traits\Common;

use App\Actions\Common\BaseJsonResource;
use App\Actions\Common\BaseModel;
use App\Actions\Common\BaseResourceCollection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection as IlluminateSupportCollection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @property string $modelClass
 */
trait HasApiResource
{
    /**
     * @param Collection|Paginator $collection
     * @return ResourceCollection
     */
    public function resourceCollection(Paginator|Collection|IlluminateSupportCollection $collection): ResourceCollection
    {
        return new BaseResourceCollection($collection, $this->modelClass);
    }

    /**
     * @param \App\Actions\Common\BaseModel $model
     * @return JsonResource
     */
    public function individualResource(BaseModel|Role|Permission $model): JsonResource
    {
        return new BaseJsonResource($model);
    }
}
