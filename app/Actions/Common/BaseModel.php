<?php

namespace App\Actions\Common;

use App\Actions\Common\BaseQueryBuilderConfig;
use \Illuminate\Support\Str;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTimeInterface;
use Throwable;

use function App\Helpers\get_all_appends;
use function App\Helpers\get_all_includes_in_camel_case;

/**
 * @property int $id
 */
abstract class BaseModel extends Model
{
    use BaseQueryBuilderConfig;

    /**
     * @var string
     */
    protected string $resourceKey = '';

    /**
     * @var array
     */
    protected array $exactFilters = [];

    /**
     * @var array
     */
    protected array $discardedFieldsInFilter = [];

    /**
     * @var array
     */
    protected array $allowedRelationshipFilters = [];

    /**
     * @var array
     */
    protected array $allowedAppends = [];

    /**
     * @var array
     */
    protected array $allowedIncludes = [];

    /**
     * @var array
     */
    protected array $excludedFromInputs = [];

    /**
     * @var array
     */
    protected array $excludedFromCreateInputs = [];

    /**
     * @var array
     */
    protected array $excludedFromUpdateInputs = [];

    /**
     * @var array
     */
    protected array $searchableRelationships = [];

    /**
     * @return int
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getPerPage(): int
    {
        $request = Container::getInstance()->make('request');
        if ($request && $request->input('per_page')) {
            return $request->input('per_page');
        }

        return $this->perPage;
    }

    /**
     * @return array
     */
    public function getExcludedFromCreateInputs(): array
    {
        return array_merge($this->excludedFromInputs, $this->excludedFromCreateInputs);
    }

    /**
     * @return array
     */
    public function getExcludedFromUpdateInputs(): array
    {
        return array_merge($this->excludedFromInputs, $this->excludedFromUpdateInputs);
    }

    /**
     * @return array
     */
    public function getExactFilters(): array
    {
        return array_merge($this->exactFilters, [
            $this->primaryKey
        ]);
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return string
     */
    public function guessResourceKey(): string
    {
        if ($this->resourceKey !== '') {
            return Str::plural($this->resourceKey);
        }
        return $this->getTable();
    }

    /**
     * @return array
     */
    public function toSearchableArray(): array
    {
        foreach ($this->searchableRelationships as $relationship) {
            if (!$this->relationLoaded($relationship)) {
                $this->load($relationship);
            }
        }
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $dates = [];
        foreach ($this->casts as $key => $cast) {
            if (in_array($cast, ['date', 'datetime', 'immutable_date', 'immutable_datetime'])) {
                $dates[] = $key;
            }
        }
        $dates = array_merge($dates, $this->dates ?? []);
        foreach ($dates as $dateKey) {
            if ($array[$dateKey] ?? false) {
                try {
                    $date = Carbon::parse($array[$dateKey]);
                    $array[$dateKey . '_date_formatted'] = $date->format($user?->date_format ?? 'Y-m-d');
                    $array[$dateKey . '_time_formatted'] = $date->format($user?->time_format ?? 'H:i:s');
                } catch (Throwable) { // @phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
                }
            }
        }
        return $array;
    }

    /**
     * @return BaseModel
     */
    public function applyQueryBuilder(): static
    {
        return $this->load(get_all_includes_in_camel_case())
            ->append(get_all_appends());
    }

    /**
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        return false;
    }
}