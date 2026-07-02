<?php

namespace App\Nova;

use App\Nova\Metrics\TotalUsers;
use Illuminate\Http\Request;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class User extends Resource
{
    use PasswordValidationRules;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email', 'organization', 'job_title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            // ── Identity ──────────────────────────────────────────────────
            ID::make()->sortable(),

            Avatar::make('Avatar', 'avatar_url')
                ->maxWidth(40)
                ->onlyOnIndex(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:191')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Select::make('Role')
                ->options([
                    'user'       => 'User',
                    'admin'      => 'Admin',
                    'superAdmin' => 'Super Admin',
                ])
                ->displayUsingLabels()
                ->sortable()
                ->rules('required'),

            Badge::make('Role')->map([
                'user'       => 'info',
                'admin'      => 'warning',
                'superAdmin' => 'danger',
            ])->onlyOnIndex(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules($this->passwordRules())
                ->updateRules($this->optionalPasswordRules()),

            // ── Profile ───────────────────────────────────────────────────
            new Panel('Profile', [
                Avatar::make('Avatar', 'avatar_url')
                    ->nullable()
                    ->hideFromIndex(),

                Text::make('Phone')
                    ->nullable()
                    ->rules('nullable', 'max:30'),

                Text::make('Job Title', 'job_title')
                    ->nullable()
                    ->rules('nullable', 'max:100'),

                Text::make('Organization')
                    ->nullable()
                    ->rules('nullable', 'max:150'),

                Select::make('Timezone')
                    ->options(collect(timezone_identifiers_list())
                        ->mapWithKeys(fn ($tz) => [$tz => $tz])
                        ->all())
                    ->nullable()
                    ->searchable()
                    ->default('UTC'),

                Select::make('Locale')
                    ->options([
                        'en' => 'English',
                        'fr' => 'French',
                        'de' => 'German',
                        'es' => 'Spanish',
                        'ar' => 'Arabic',
                    ])
                    ->nullable()
                    ->displayUsingLabels()
                    ->default('en'),

                KeyValue::make('Preferences')
                    ->nullable()
                    ->keyLabel('Setting')
                    ->valueLabel('Value'),
            ]),

            // ── Security & Audit ──────────────────────────────────────────
            new Panel('Security', [
                Boolean::make('2FA Enabled', 'two_factor_confirmed_at')
                    ->trueValue(fn ($val) => $val !== null)
                    ->readonly()
                    ->hideWhenCreating()
                    ->hideWhenUpdating(),

                Text::make('Last Login IP', 'last_login_ip')
                    ->nullable()
                    ->readonly()
                    ->hideWhenCreating()
                    ->hideWhenUpdating(),

                DateTime::make('Last Login At', 'last_login_at')
                    ->nullable()
                    ->readonly()
                    ->hideWhenCreating()
                    ->hideWhenUpdating(),

                Text::make('Failed Login Attempts', 'failed_login_attempts')
                    ->readonly()
                    ->hideWhenCreating()
                    ->hideWhenUpdating(),

                DateTime::make('Locked Until', 'locked_until')
                    ->nullable()
                    ->hideWhenCreating()
                    ->hideWhenUpdating(),

                DateTime::make('Email Verified At', 'email_verified_at')
                    ->nullable()
                    ->readonly()
                    ->hideWhenCreating()
                    ->hideWhenUpdating(),
            ]),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request)
    {
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
