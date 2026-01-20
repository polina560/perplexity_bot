<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Topic;

use App\Models\Topic;
use App\MoonShine\Resources\Topic\Pages\TopicIndexPage;
use App\MoonShine\Resources\Topic\Pages\TopicFormPage;
use App\MoonShine\Resources\Topic\Pages\TopicDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Topic, TopicIndexPage, TopicFormPage, TopicDetailPage>
 */
class TopicResource extends ModelResource
{
    protected string $model = Topic::class;

    protected string $title = 'Тема';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            TopicIndexPage::class,
            TopicFormPage::class,
            TopicDetailPage::class,
        ];
    }
}
