<?php

namespace App\Providers\Filament;

use Filament\PanelProvider;
use Filament\Panel;
use Filament\Support\Colors\Color;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('user')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\\Filament\\User\\Resources')
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\\Filament\\User\\Pages')
            ->middleware([
                'auth',
                'verified',
            ])
            ->authMiddleware([
                'auth',
            ])
            ->brandName('Support Ticket System')
            ->navigationGroups([
                'Tickets',
            ])
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                'panels::head.end',
                fn (): string => '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">',
            );
    }
}