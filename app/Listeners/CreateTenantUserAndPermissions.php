<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Team;
use App\Mail\SignInEmail;
use App\Models\Admin;
use App\Models\TeamInvitation;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\SignUpNotification;
use Stancl\Tenancy\Events\TenantCreated;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class CreateTenantUserAndPermissions implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TenantCreated  $event
     * @return void
     */
    public function handle(TenantCreated $event): void
    {
        $tenantId = $event->tenant->id;
        $tenant = Tenant::find($tenantId);

        $tenant->run(function () use ($tenant) {
            $ownerRole = Role::firstOrCreate(['name' => 'Owner', "guard_name" => "admin"]);
            $user = Admin::create([
                'first_name' => $tenant->name,
                'email' => $tenant->email,
                'password' => bcrypt($tenant->name . "######"),
            ])->assignRole($ownerRole);

            // Create a team for the tenant
            $team = Team::create([
                'name' => $tenant->name,
                'personal_team' => false,
                'user_id' => $user->id,
            ]);

            // $team->users()->attach($user->id, ['role' => 'Owner']);

            $token = app('auth.password.broker')->createToken($user);

            $resetLink = Filament::getResetPasswordUrl($token, $user);

            $user->notify(new SignUpNotification($user->first_name, $resetLink, $user->email));
        });
    }
}
