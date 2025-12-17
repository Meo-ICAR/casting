<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Assets\Js;
use App\Filament\Widgets\ProjectSlider;
//use \App\Http\Middleware\RedirectIfAuthenticated;
use App\Filament\Pages\Auth\CustomRegister;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
          ->registration(CustomRegister::class) // <--- Usa la tua classe custom
            ->profile()
  ->passwordReset()
        ->emailVerification()
        ->emailChangeVerification()
             ->authMiddleware([
            \App\Http\Middleware\Authenticate::class,
        ])
        ->authGuard('web')
        ->authPasswordBroker('users')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
              //  AccountWidget::class,
              //  FilamentInfoWidget::class,
                ProjectSlider::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
              //  AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
              //  DisableBladeIconComponents::class,
              //  DispatchServingFilamentEvent::class,
               //  \Filament\Http\Middleware\Authenticate::class,
             //  RedirectIfAuthenticated::class

            ])

            ->authMiddleware([
                Authenticate::class,
            ])
                        ->brandLogo(asset('castingprologo.png'))
            ->brandLogoHeight('50px')
                        ->colors([
                'primary' => Color::Amber,
            ])
            ;

    }
}
