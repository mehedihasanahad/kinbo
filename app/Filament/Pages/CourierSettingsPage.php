<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\Couriers\PathaoService;
use App\Services\Couriers\SteadfastCourierService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class CourierSettingsPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Courier';
    protected static ?string $navigationLabel = 'Courier Settings';
    protected static ?string $title           = 'Courier Settings';
    protected static ?int    $navigationSort  = 10;

    protected static string $view = 'filament.pages.courier-settings-page';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->hasPermission('edit_settings'));
    }

    public array $steadfast = [];
    public array $pathao    = [];

    public function mount(): void
    {
        $this->steadfast = [
            'steadfast_api_key'      => Setting::get('steadfast_api_key', ''),
            'steadfast_secret_key'   => Setting::get('steadfast_secret_key', ''),
            'steadfast_bearer_token' => Setting::get('steadfast_bearer_token', ''),
        ];

        $this->pathao = [
            'pathao_client_id'     => Setting::get('pathao_client_id', ''),
            'pathao_client_secret' => Setting::get('pathao_client_secret', ''),
            'pathao_username'      => Setting::get('pathao_username', ''),
            'pathao_password'      => Setting::get('pathao_password', ''),
            'pathao_sandbox'       => (bool) Setting::get('pathao_sandbox', '1'),
            'pathao_store_id'      => Setting::get('pathao_store_id', ''),
        ];
    }

    protected function getForms(): array
    {
        return ['steadfastForm', 'pathaoForm'];
    }

    public function steadfastForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Steadfast Courier API')
                ->description('Credentials from your Steadfast merchant portal (Portal → API).')
                ->icon('heroicon-o-key')
                ->schema([
                    Forms\Components\TextInput::make('steadfast_api_key')
                        ->label('API Key')
                        ->nullable()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('steadfast_secret_key')
                        ->label('Secret Key')
                        ->password()
                        ->revealable()
                        ->nullable()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('steadfast_bearer_token')
                        ->label('Webhook Bearer Token')
                        ->password()
                        ->revealable()
                        ->nullable()
                        ->maxLength(255)
                        ->helperText('Used to validate incoming webhook requests from Steadfast.')
                        ->columnSpanFull(),
                ])->columns(2),
        ])->statePath('steadfast');
    }

    public function pathaoForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Pathao Courier API')
                ->description('OAuth credentials from your Pathao Merchant Portal.')
                ->icon('heroicon-o-key')
                ->schema([
                    Forms\Components\TextInput::make('pathao_client_id')
                        ->label('Client ID')
                        ->nullable()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('pathao_client_secret')
                        ->label('Client Secret')
                        ->password()
                        ->revealable()
                        ->nullable()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('pathao_username')
                        ->label('Merchant Email')
                        ->email()
                        ->nullable()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('pathao_password')
                        ->label('Merchant Password')
                        ->password()
                        ->revealable()
                        ->nullable()
                        ->maxLength(255),

                    Forms\Components\Toggle::make('pathao_sandbox')
                        ->label('Sandbox Mode')
                        ->helperText('Enable for testing. Disable for live orders.')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('pathao_store_id')
                        ->label('Store ID')
                        ->numeric()
                        ->nullable()
                        ->helperText('Your merchant store ID — fixed for all orders. Find it in Pathao Portal → Stores.')
                        ->columnSpanFull(),
                ])->columns(2),
        ])->statePath('pathao');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('check_balance')
                ->label('Steadfast Balance')
                ->icon('heroicon-o-currency-bangladeshi')
                ->color('gray')
                ->action(function (): void {
                    try {
                        $service = app(SteadfastCourierService::class);

                        if (! $service->isConfigured()) {
                            Notification::make()
                                ->title('Steadfast not configured')
                                ->body('Save your Steadfast API credentials first.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $response = $service->getCurrentBalance();
                        $balance  = $response['current_balance'] ?? 'N/A';

                        Notification::make()
                            ->title('Steadfast Balance')
                            ->body("Current balance: ৳{$balance}")
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Balance check failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('test_steadfast')
                ->label('Test Steadfast')
                ->icon('heroicon-o-signal')
                ->color('info')
                ->action(function (): void {
                    try {
                        $service = app(SteadfastCourierService::class);

                        if (! $service->isConfigured()) {
                            Notification::make()
                                ->title('Steadfast not configured')
                                ->body('Save your Steadfast credentials first.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $service->getCurrentBalance();

                        Notification::make()
                            ->title('Steadfast connected')
                            ->body('API credentials are valid.')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Steadfast connection failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('test_pathao')
                ->label('Test Pathao')
                ->icon('heroicon-o-signal')
                ->color('warning')
                ->action(function (): void {
                    try {
                        $service = app(PathaoService::class);

                        if (! $service->isConfigured()) {
                            Notification::make()
                                ->title('Pathao not configured')
                                ->body('Fill in Pathao credentials and save first.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $stores     = $service->fetchStores();
                        $storeCount = \count($stores);
                        $storeNames = collect($stores)->pluck('store_name')->take(3)->implode(', ');

                        Notification::make()
                            ->title('Pathao connected')
                            ->body("Found {$storeCount} store(s): {$storeNames}")
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Pathao connection failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        foreach ($this->steadfast as $key => $value) {
            Setting::set($key, (string) $value, 'courier');
        }

        foreach ($this->pathao as $key => $value) {
            Setting::set($key, \is_bool($value) ? ($value ? '1' : '0') : (string) $value, 'courier');
        }

        Cache::forget('pathao_token');
        Cache::forget('settings.public');

        Notification::make()->title('Courier settings saved.')->success()->send();
    }
}
