<?php

namespace App\Nova;

use App\Models\Subscription as SubscriptionModel;
use App\Nova\Metrics\TotalSubscriptions;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Subscription extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Subscription>
     */
    public static $model = SubscriptionModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array<int, string>
     */
    public static $search = [
        'id',
        'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:255'),

            Text::make('Time', 'created_at')
                ->displayUsing(fn ($date) => $date ? $date->format('H:i:s') : '')
                ->sortable()
                ->onlyOnIndex(),
                
            Text::make('Date', 'created_at')
                ->displayUsing(fn ($date) => $date ? $date->format('Y-m-d') : '')
                ->sortable()
                ->onlyOnIndex(),
                
            DateTime::make('Created At')
                ->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [
            new TotalSubscriptions,
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
