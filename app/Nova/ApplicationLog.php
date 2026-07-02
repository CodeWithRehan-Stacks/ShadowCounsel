<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class ApplicationLog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ApplicationLog>
     */
    public static $model = \App\Models\ApplicationLog::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'level_name', 'message',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            
            Text::make('Level', 'level_name')
                ->sortable(),
                
            Text::make('Message', 'message')
                ->sortable()
                ->displayUsing(function ($value) {
                    return strlen($value) > 100 ? substr($value, 0, 100) . '...' : $value;
                })
                ->onlyOnIndex(),
                
            Text::make('Message', 'message')
                ->hideFromIndex(),
                
            Code::make('Context', 'context')
                ->json(),
                
            Code::make('Extra', 'extra')
                ->json(),
                
            Text::make('Time', 'created_at')
                ->displayUsing(fn ($date) => $date ? $date->format('H:i:s') : '')
                ->sortable()
                ->onlyOnIndex(),
                
            Text::make('Date', 'created_at')
                ->displayUsing(fn ($date) => $date ? $date->format('Y-m-d') : '')
                ->sortable()
                ->onlyOnIndex(),
                
            DateTime::make('Created At', 'created_at')
                ->hideFromIndex(),
        ];
    }

    /**
     * Determine if this resource is authorized to create.
     */
    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

    /**
     * Determine if this resource is authorized to update.
     */
    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
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
