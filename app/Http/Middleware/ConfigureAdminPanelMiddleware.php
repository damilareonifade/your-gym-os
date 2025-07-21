<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ConfigureAdminPanelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Load Branding once
        $this->brand = Branding::first();

        $storage_path = storage_path();
        if (!is_dir($storage_path)) {
            mkdir("$storage_path/framework/cache", 0777, true);
        }

        // Set Filament primary color dynamically
        FilamentColor::register([
            'primary' => \Filament\Support\Colors\Color::hex($this->getTenantBrandColor())
        ]);

        // Configure Filament branding
        Filament::getCurrentPanel()
            ->brandName('Bankit')
            ->brandLogo($this->getBrandLogo())
            ->brandLogoHeight('3rem');

        return $next($request);
    }

    /**
     * Get the tenant's brand color, or use a default.
     */
    private function getTenantBrandColor(): string
    {
        return $this->brand?->color ?? '#6366f1';
    }

    /**
     * Get the tenant's brand logo, or use a default.
     */
    private function getBrandLogo(): string
    {
        return $this->brand?->brand_logo
            ? Storage::url($this->brand->brand_logo)
            : "https://bankitafrica.com/_next/static/media/Bankit-Logo-v2-cropped.9a7bc060.png";
    }
}
