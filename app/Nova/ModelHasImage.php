<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class ModelHasImage extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ModelHasImage>
     */
    public static $model = \App\Models\ModelHasImage::class;

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
            ID::make(),
            Image::make('Image', 'path')
                ->disk('s3')
                ->path('images')
                ->hideFromIndex(),

            // Polymorphic relation (MorphToSelect in Filament)
            MorphTo::make('Imageable')
                ->types([
                    Tenant::class
                ]),

            // Repeater equivalent for "icon"
            // (Nova doesn’t have native repeaters, so you typically use KeyValue or JSON field packages)
            KeyValue::make('Icon')
                ->keyLabel('Name')
                ->valueLabel('Color Values')
                ->rules('json'),

            // Repeater equivalent for "tag"
            KeyValue::make('Tag')
                ->keyLabel('Name')
                ->valueLabel('Color Values')
                ->rules('json'),

            // Toggles (booleans in Nova)
            Boolean::make('Has Image', 'has_image')
                ->default(false),

            Boolean::make('Has Icon', 'has_icon')
                ->default(false),
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
        return [];
    }
}
