<?php

namespace App\Nova\Actions;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Notifications\SignUpNotification;
use Illuminate\Support\Facades\Password;

class ResendTenantEmail extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $tenant) {
            // Extract tenant's name and email
            $tenantName = $tenant->name;
            $domain = $tenant->domains()->first()->domain;
            $currentUrl = config("app.url");
            $tenantEmail = $tenant->email;
            $resetLink = $tenant->run(function () use ($domain, $tenantEmail) {
                $user = Admin::where("email", $tenantEmail)->first();
                $token = Password::broker('admin')->createToken($user);

                if (config('app.env') === 'production') {
                    URL::forceRootUrl(config('app.url'));
                    URL::forceScheme('https');
                } else {
                    URL::forceRootUrl($domain);
                    URL::forceScheme("http");
                }

                $resetLink = Filament::getResetPasswordUrl($token, $user);

                return $resetLink;
            });
            $tenant->notify(new SignUpNotification($tenantName, $resetLink, $tenantEmail));
            if (config('app.env') === 'production') {
                URL::forceRootUrl(config('app.url'));
                URL::forceScheme('https');
            } else {
                URL::forceRootUrl(config("app.url"));
                URL::forceScheme("http");
            }
        }

        return Action::message('Emails have been sent successfully!');
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
