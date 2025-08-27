<?php

namespace App\Listeners;

use App\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Stancl\Tenancy\Events\TenantCreated;

class CreateTenantDomain
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenantCreated $event): void
    {
        $tenantId = $event->tenant->id;
        $tenant = Tenant::find($tenantId);
        $domain = $tenant->domains()->create([
            'domain' => $tenant->name . '.' . config('services.tenancy.tenant_domain'),
        ]);
    }
}