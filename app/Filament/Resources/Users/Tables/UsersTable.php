<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('first_name')
                    ->label('Full Name')
                    ->searchable(
                        query: function (Builder $query, string $search): Builder {
                            return $query->where(function ($subQuery) use ($search) {
                                $subQuery
                                    ->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
                            });
                        }
                    )
                    ->searchable()
                    ->formatStateUsing(fn($record) => "{$record->first_name} {$record->last_name}"),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('two_factor_confirmed_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label("Joined At")
                    ->dateTime()
                    ->sortable(),
                // ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->deferLoading()
            ->searchable()
            ->searchUsing(fn(Builder $query, string $search) => $query->whereKey(User::search($search)->keys()))
            ->filters([
                // Filter by email verified status
                TernaryFilter::make('email_verified')
                    ->label('Email Verified')
                    ->nullable() // allows "Any"
                    ->trueLabel('Verified')
                    ->falseLabel('Not Verified')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn(Builder $query) => $query->whereNull('email_verified_at'),
                    ),

                // Filter by 2FA confirmation
                // TernaryFilter::make('two_factor')
                //     ->label('Two-Factor Auth')
                //     ->nullable()
                //     ->trueLabel('Enabled')
                //     ->falseLabel('Disabled')
                //     ->queries(
                //         true: fn(Builder $query) => $query->whereNotNull('two_factor_confirmed_at'),
                //         false: fn(Builder $query) => $query->whereNull('two_factor_confirmed_at'),
                //     ),

                // Filter by registration date range
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                // Filter by role (if using Spatie or similar)
                // SelectFilter::make('role')
                //     ->label('Role')
                //     ->options([
                //         'super_admin' => 'Super Admin',
                //         'admin' => 'Admin',
                //         'user' => 'User',
                //     ])
                //     ->query(fn(Builder $query, $value): Builder => $query->whereHas('roles', fn($q) => $q->where('name', $value))),
            ])

            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}