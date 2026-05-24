<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ProfilePage;
use App\Filament\Widgets\OrdersByStatusChart;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TopProductsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    private function resolveFavicon(): ?string
    {
        if (! Schema::hasTable('settings')) {
            return null;
        }
        $path = \App\Models\Setting::get('site_favicon', '');
        return $path ? Storage::url($path) : null;
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile(ProfilePage::class)
            ->brandName(config('app.name') . ' Admin')
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->favicon($this->resolveFavicon())
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StatsOverview::class,
                RevenueChart::class,
                OrdersChart::class,
                OrdersByStatusChart::class,
                TopProductsWidget::class,
                Widgets\AccountWidget::class,
            ])
            ->navigationGroups([
                'Catalog',
                'Commerce',
                'Courier',
                'Users & Access',
                'Settings',
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
