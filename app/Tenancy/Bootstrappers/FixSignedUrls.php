<?php

namespace App\Tenancy\Bootstrappers;

use Illuminate\Support\Facades\URL;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Contracts\TenancyBootstrapper;


class FixSignedUrls implements TenancyBootstrapper
{
    public function bootstrap(Tenant $tenant)
    {
        URL::formatHostUsing(function () use ($tenant) {
            $protocol = config("app.env") === "production" ? 'https://' : 'http://';
            $tenantDomain = $tenant->domains->first()->domain;
            $fullDomain = $tenantDomain;

            return $protocol . $fullDomain;
        });
    }

    public function revert()
    {
        URL::formatHostUsing(function () {
            return config('app.url');
        });
    }

}
