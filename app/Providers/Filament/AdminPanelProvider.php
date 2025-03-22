<?php

namespace App\Providers\Filament;

use Filament\Contracts\Plugin;
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
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Resources\ReservationResource;
use App\Filament\Resources\RoomResource;
use App\Filament\Resources\UserResource;
use App\Models\ReservationUser;
use Filament\Navigation\NavigationGroup;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use App\Filament\Resources\BookingRoomResource;
use App\Filament\Resources\BookingUserResource;
use BladeUI\Icons\Components\Icon;
use League\CommonMark\Delimiter\Bracket;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop(true)
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandName('GOGOGOGO LSP')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ])

            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                    ->items([
                        NavigationItem::make('Dashboard')
                        ->icon('heroicon-o-chart-bar')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                        ->url(fn (): string => Dashboard::getUrl())
                        ->visible(fn () => !auth()->user()?->hasRole('user')),

                        NavigationItem::make('BookingRoom')
                        ->icon('heroicon-o-calendar')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.BookingRooms.index'))
                        ->url(fn (): string => BookingRoomResource::getUrl('index'))
                        ->visible(fn () => !auth()->user()?->hasRole('admin')),

                        NavigationItem::make('MyBooking')
                        ->icon('heroicon-o-inbox-arrow-down')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.MyBookings.index'))
                        ->url(fn (): string => BookingUserResource::getUrl('index'))
                        ->visible(fn () => !auth()->user()?->hasRole('admin')),

                    ]),

                    NavigationGroup::make('Management')
                    ->items(
                        array_merge(
                            auth()->user()?->hasRole('admin') ? ReservationResource::getNavigationItems() : [],
                            auth()->user()?->hasRole('admin') ? RoomResource::getNavigationItems() : []
                        )
                    ),

                    NavigationGroup::make('Setings')
    ->items(
        array_merge(
            auth()->user()?->hasRole('admin') ? UserResource::getNavigationItems() : [],
            auth()->user()?->hasRole('admin') ? [
                NavigationItem::make('Roles')
                    ->icon('heroicon-o-user-group')
                    ->isActiveWhen(fn (): bool => request()->routeIs([
                        'filament.admin.resources.Roles.index',
                        'filament.admin.resources.Roles.create',
                        'filament.admin.resources.Roles.view',
                        'filament.admin.resources.Roles.edit'
                    ]))
                    ->url(fn (): string => '/admin/roles'),

                NavigationItem::make('Permissions')
                    ->icon('heroicon-o-lock-closed')
                    ->isActiveWhen(fn (): bool => request()->routeIs([
                        'filament.admin.resources.Permissions.index',
                        'filament.admin.resources.Permissions.create',
                        'filament.admin.resources.Permissions.view',
                        'filament.admin.resources.Permissions.edit'
                    ]))
                    ->url(fn (): string => '/admin/permissions'),
            ] : []
        )
    ),

                ]);
            });




    }




}
