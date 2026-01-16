<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Prompt;

use App\Models\Prompt;
use App\MoonShine\Resources\Prompt\Pages\PromptIndexPage;
use App\MoonShine\Resources\Prompt\Pages\PromptFormPage;
use App\MoonShine\Resources\Prompt\Pages\PromptDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Prompt, PromptIndexPage, PromptFormPage, PromptDetailPage>
 */
class PromptResource extends ModelResource
{
    protected string $model = Prompt::class;

    protected string $title = 'Промпты';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            PromptIndexPage::class,
            PromptFormPage::class,
            PromptDetailPage::class,
        ];
    }
}
