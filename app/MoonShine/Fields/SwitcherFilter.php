<?php

declare(strict_types=1);

namespace App\MoonShine\Fields;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MoonShine\UI\Fields\Select;

class SwitcherFilter extends Select
{
    public function __construct(
        Closure|string|null $label = null,
        ?string $column = null,
        ?Closure $formatted = null,
    ) {
        parent::__construct($label, $column, $formatted);
        $this
            ->options([true => 'Да', false => 'Нет'])
            ->nullable()
            ->onApply(
                function ($query, $value) use ($column): void {
                    assert(is_string($column));
                    /**
                     * @var Builder<Model> $query
                     * @var string|int|bool|null $value
                     */
                    if (!is_null($value)) {
                        $query->where($column, (bool) (int) $value);
                    }
                }
            );
    }
}
