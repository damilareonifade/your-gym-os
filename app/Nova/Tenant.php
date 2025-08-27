<?php

namespace App\Nova;

use App\Nova\Actions\ResendTenantEmail;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Tenant extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Tenant>
     */
    public static $model = \App\Models\Tenant::class;

    public static $group = 'Tenancy';

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
        'id',
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

            Fields\Gravatar::make(),

            Fields\Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Fields\Text::make('Username')
                ->sortable()
                ->rules('required', 'max:254')
                ->creationRules('unique:tenants,username'),

            Fields\Email::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254'),

            Fields\Number::make('Phone Number')->hideFromIndex(),

            Fields\Text::make('Code')
                ->sortable()
                ->rules('max:254'),

            Fields\Select::make('Status')
                ->sortable()
                ->options(static::$model::$statusType)->displayUsingLabels(),

            Fields\Code::make('data')
                ->json()
                ->rules('nullable', 'json')
                ->hideFromIndex(),

            // BelongsToMany::make('providers', 'providers', \App\Nova\Providers::class),

        ];
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
        return [
            new ResendTenantEmail,
        ];
    }
}
