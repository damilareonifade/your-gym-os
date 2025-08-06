<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;

class Admin extends Model implements FilamentUser, HasAvatar, HasName
{

    use HasUlids;
    protected $guarded = ['id'];

    protected $hidden = [
        'app_authentication_secret',
        'app_authentication_recovery_codes',
    ];

    protected $casts = [
        'password' => "hashed",
        'app_authentication_recovery_codes' => 'encrypted:array',
        'app_authentication_secret' => 'encrypted',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
    }

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function getAppAuthenticationSecret(): ?string
    {

        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(?string $secret): void
    {

        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {

        return $this->email;
    }

    /**
     * @return ?array<string>
     */
    public function getAppAuthenticationRecoveryCodes(): ?array
    {

        return $this->app_authentication_recovery_codes;
    }

    /**
     * @param  array<string> | null  $codes
     */
    public function saveAppAuthenticationRecoveryCodes(?array $codes): void
    {

        $this->app_authentication_recovery_codes = $codes;
        $this->save();
    }
}
