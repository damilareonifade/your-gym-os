<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class NavigationMenu extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\NavigationMenu>
     */
    public static $model = \App\Models\NavigationMenu::class;

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
            Text::make('Name')->rules('required')->help('Name of the menu to be displayed'),
            Text::make('Tag')->help('Tag of the menu to be displayed'),
            Select::make('Tag Type')->options([
                'success' => 'Success',
                'danger' => 'Danger',
                'info' => 'Info',
            ]),
            Text::make('Description')->help('Description of the menu to be displayed'),
            BelongsTo::make('Tenant', 'Tenant', Tenant::class)->searchable()->nullable()->filterable(),
            MorphMany::make('Images', 'Images', ModelHasImage::class),
            MorphTo::make('Model')->types([
                Tenant::class
            ])->nullable()->searchable()->filterable(),
            Select::make('Type')->options([
                'top' => 'Top Menu',
                'main' => 'Main Menu',
                'scroll' => 'Scroll Menu',
                'bottom' => 'Bottom',
                'middle' => 'Middle Nav',
            ])->rules('required')->filterable(),
            Boolean::make('Android')->filterable(),
            Boolean::make('IOS')->filterable(),
            Boolean::make('Web')->filterable(),
            Boolean::make('Default Page'),
            Select::make('Link Type')->options([
                'link' => 'Link',
                'modal' => 'Modal',
            ])->rules('required')->filterable(),
            Select::make('Web Link')
                ->options(
                    [
                        null => 'None',
                        '/' => 'Home',
                        'transactions' => 'Transactions',
                        'transfer' => 'Transfer',
                        'bills-payment' => 'Bills Payment',
                        'BillModal' => 'Single Bill',
                        'accounts' => 'Wallets',
                        'accounts/fund-account' => 'Fund Wallet',
                        'contest-management' => 'Contest Management',
                        'payment-links' => 'Payment Links',
                        'settings' => 'Settings',
                        'settings/profile' => 'Profile',
                        'New Contest' => 'New Contest',
                        'log-out' => 'Log Out',
                        "scheduled-transaction" => 'Schedule Transactions',
                        'credit' => "Credit"
                    ]
                ),

            Select::make('Mobile Link')
                ->options(
                    [
                        null => 'None',
                        'Transfer' => 'Transfer',
                        'BillProviders' => 'Bill Categories',
                        'AllBills' => 'All Bills',
                        'BillVent' => 'Bill Vent',
                        'SavingsDashboard' => 'Savings',
                        'PaymentLinks' => 'Payment Links',
                        'ComingSoon' => 'Coming Soon',
                        'Pos' => 'POS',
                        'MoreBills' => 'More Tools',
                        'Transactions' => 'Transactions',
                        'Help' => 'Help',
                        'Account' => 'Account',
                        'Profile' => 'Profile',
                        'FundWallet' => 'Fund Wallet',
                        'CreateVirtualAccount' => 'Create Virtual Account',
                        'Preferences' => 'Preferences',
                        'Referral' => 'Referral',
                        'Security' => 'Security',
                        "Home" => "Home",
                        "Beneficiaries" => "Beneficiaries",
                        "RecentTransactions" => "Recent Transactions",
                        "ScheduledTransactions" => 'Schedule Transactions',
                        "Credit" => "Credit"
                    ]
                ),
            BelongsTo::make('Parent Menu', 'parent', NavigationMenu::class)->searchable()->nullable(),
            HasMany::make('Sub Menus', 'subMenus', NavigationMenu::class)->nullable(),
            // BelongsTo::make('Country', 'Country', Country::class)->searchable()->nullable()->filterable(),
            Number::make('Rank')->default(0),
            Boolean::make('Status')->rules('required')->filterable(),
            Boolean::make('Is Available')->rules('required')->filterable(),

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