<?php

namespace App\Http\Filters;
use Illuminate\Database\Eloquent\Builder;

class ItemFilter  extends AbstractFilter
{
    public const TITLE = 'title';
    public const LIST_ID = 'list_id';

    protected function getCallbacks(): array
    {
        return [
            self::TITLE => [$this, 'title'],
            self::LIST_ID => [$this, 'listId']
        ];
    }

    public function title(Builder $builder, $value): void
    {
        $builder->where('title', 'like', "%{$value}%");
    }

    public function listId(Builder $builder, $value): void
    {
        $builder->where('list_id', $value);
    }

}
