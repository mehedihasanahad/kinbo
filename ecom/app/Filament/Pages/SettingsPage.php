<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Store Settings';
    protected static ?string $title = 'Store Settings';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.settings-page';

    // Form state
    public array $general  = [];
    public array $contact  = [];
    public array $payment  = [];
    public array $social   = [];

    // Branding — kept flat (no statePath) so FileUpload can store files properly
    public ?array $site_logo    = [];
    public ?array $site_favicon = [];

    public function mount(): void
    {
        $this->general = [
            'site_name'             => Setting::get('site_name', config('app.name')),
            'site_tagline'          => Setting::get('site_tagline', ''),
            'site_description'      => Setting::get('site_description', ''),
            'default_locale'        => Setting::get('default_locale', 'en'),
            'currency'              => Setting::get('currency', 'BDT'),
            'currency_symbol'       => Setting::get('currency_symbol', '৳'),
            'announcement_bar_text' => Setting::get('announcement_bar_text', ''),
        ];

        $this->contact = [
            'contact_email'   => Setting::get('contact_email', ''),
            'contact_phone'   => Setting::get('contact_phone', ''),
            'contact_address' => Setting::get('contact_address', ''),
            'contact_city'    => Setting::get('contact_city', ''),
        ];

        $this->payment = [
            'bkash_merchant_number'     => Setting::get('bkash_merchant_number', env('BKASH_MERCHANT_NUMBER', '')),
            'bkash_merchant_name'       => Setting::get('bkash_merchant_name', env('BKASH_MERCHANT_NAME', '')),
            'nagad_merchant_number'     => Setting::get('nagad_merchant_number', env('NAGAD_MERCHANT_NUMBER', '')),
            'nagad_merchant_name'       => Setting::get('nagad_merchant_name', env('NAGAD_MERCHANT_NAME', '')),
            'sslcommerz_store_id'       => Setting::get('sslcommerz_store_id', ''),
            'sslcommerz_store_password' => Setting::get('sslcommerz_store_password', ''),
            'sslcommerz_is_live'        => Setting::get('sslcommerz_is_live', '0'),
            'cod_enabled'               => Setting::get('cod_enabled', '1'),
            'free_shipping_above'       => Setting::get('free_shipping_above', ''),
        ];

        $this->social = [
            'facebook_url'  => Setting::get('facebook_url', ''),
            'instagram_url' => Setting::get('instagram_url', ''),
            'youtube_url'   => Setting::get('youtube_url', ''),
            'twitter_url'   => Setting::get('twitter_url', ''),
        ];

        // Hydrate flat FileUpload properties from stored paths.
        // Filament v3 FileUpload expects [path => path] when loading existing files.
        $logoPath    = Setting::get('site_logo', '');
        $faviconPath = Setting::get('site_favicon', '');

        $this->site_logo    = $logoPath    ? [$logoPath    => $logoPath]    : [];
        $this->site_favicon = $faviconPath ? [$faviconPath => $faviconPath] : [];
    }

    protected function getForms(): array
    {
        return ['brandingForm', 'generalForm', 'contactForm', 'paymentForm', 'socialForm'];
    }

    public function brandingForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('site_logo')
                ->label('Store Logo')
                ->image()
                ->disk('public')
                ->visibility('public')
                ->directory('settings')
                ->imagePreviewHeight('100')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                ->maxSize(1024)
                ->nullable()
                ->helperText('Displayed in the storefront header. Recommended: 200×60px, transparent PNG or SVG.'),

            Forms\Components\FileUpload::make('site_favicon')
                ->label('Favicon')
                ->image()
                ->disk('public')
                ->visibility('public')
                ->directory('settings')
                ->imagePreviewHeight('100')
                ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/webp'])
                ->maxSize(256)
                ->nullable()
                ->helperText('Browser tab icon. 32×32px PNG or ICO recommended.'),
        ])->columns(2);
        // No ->statePath() — binds directly to $this->site_logo and $this->site_favicon
    }

    public function generalForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('site_name')->required()->maxLength(100),
            Forms\Components\TextInput::make('site_tagline')->maxLength(191)->nullable(),
            Forms\Components\Textarea::make('site_description')->rows(2)->nullable()->columnSpanFull(),
            Forms\Components\Select::make('default_locale')
                ->options(['en' => 'English', 'bn' => 'Bengali'])->required(),
            Forms\Components\TextInput::make('currency')->maxLength(10)->default('BDT'),
            Forms\Components\TextInput::make('currency_symbol')->maxLength(5)->default('৳'),

            Forms\Components\Section::make('Announcement Bar')->schema([
                Forms\Components\TextInput::make('announcement_bar_text')
                    ->label('Announcement Text')
                    ->nullable()
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->helperText('Shown in the top bar. Leave blank to hide. Supports basic HTML like <strong>. E.g.: Free shipping over ৳999 &amp;nbsp;|&amp;nbsp; Use code &lt;strong&gt;WELCOME10&lt;/strong&gt; for 10% off'),
            ])->columnSpanFull(),
        ])->statePath('general')->columns(2);
    }

    public function contactForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('contact_email')->email()->nullable(),
            Forms\Components\TextInput::make('contact_phone')->nullable(),
            Forms\Components\TextInput::make('contact_city')->nullable(),
            Forms\Components\Textarea::make('contact_address')->rows(2)->nullable()->columnSpanFull(),
        ])->statePath('contact')->columns(2);
    }

    public function paymentForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('bKash')->schema([
                Forms\Components\TextInput::make('bkash_merchant_number')->label('Merchant Number')->nullable(),
                Forms\Components\TextInput::make('bkash_merchant_name')->label('Merchant Name')->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Nagad')->schema([
                Forms\Components\TextInput::make('nagad_merchant_number')->label('Merchant Number')->nullable(),
                Forms\Components\TextInput::make('nagad_merchant_name')->label('Merchant Name')->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('SSLCommerz')->schema([
                Forms\Components\TextInput::make('sslcommerz_store_id')
                    ->label('Store ID')->nullable(),
                Forms\Components\TextInput::make('sslcommerz_store_password')
                    ->label('Store Password')->password()->revealable()->nullable(),
                Forms\Components\Toggle::make('sslcommerz_is_live')
                    ->label('Live Mode (off = sandbox)')
                    ->helperText('Enable only after testing on sandbox.')
                    ->inline(false),
            ])->columns(2),

            Forms\Components\Section::make('Other')->schema([
                Forms\Components\Toggle::make('cod_enabled')->label('Cash on Delivery Enabled')->default(true)->inline(false),
                Forms\Components\TextInput::make('free_shipping_above')
                    ->numeric()->prefix('৳')->nullable()->label('Free Shipping Above'),
            ])->columns(2),
        ])->statePath('payment');
    }

    public function socialForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('facebook_url')->url()->nullable()->label('Facebook'),
            Forms\Components\TextInput::make('instagram_url')->url()->nullable()->label('Instagram'),
            Forms\Components\TextInput::make('youtube_url')->url()->nullable()->label('YouTube'),
            Forms\Components\TextInput::make('twitter_url')->url()->nullable()->label('Twitter / X'),
        ])->statePath('social')->columns(2);
    }

    public function save(): void
    {
        $this->generalForm->validate();
        $this->contactForm->validate();

        // Persist branding files manually.
        // $this->site_logo is either:
        //   - [uuid => TemporaryUploadedFile]  — a new upload waiting to be stored
        //   - ['settings/file.png' => 'settings/file.png']  — an already-stored file (no action needed)
        //   - []  — cleared / no image
        $logoPath    = $this->storeOrKeep($this->site_logo,    'settings');
        $faviconPath = $this->storeOrKeep($this->site_favicon, 'settings');

        Setting::set('site_logo',    $logoPath,    'branding');
        Setting::set('site_favicon', $faviconPath, 'branding');

        foreach ($this->general as $key => $value) {
            Setting::set($key, $value, 'general');
        }
        foreach ($this->contact as $key => $value) {
            Setting::set($key, $value, 'contact');
        }
        foreach ($this->payment as $key => $value) {
            Setting::set($key, $value, 'payment');
        }
        foreach ($this->social as $key => $value) {
            Setting::set($key, $value, 'social');
        }

        Cache::forget('settings.public');

        Notification::make()->title('Settings saved successfully')->success()->send();
    }

    /**
     * Given the raw FileUpload state array, either:
     *  - store a new TemporaryUploadedFile to $directory on the public disk, or
     *  - return the existing path as-is, or
     *  - return '' if cleared.
     */
    private function storeOrKeep(?array $state, string $directory): string
    {
        if (empty($state)) {
            return '';
        }

        $first = array_values($state)[0];

        // New upload — Livewire TemporaryUploadedFile instance
        if ($first instanceof TemporaryUploadedFile) {
            $path = $first->store($directory, 'public');
            return $path ?: '';
        }

        // Already stored — the key is the path
        $key = array_key_first($state);
        if (is_string($key) && Storage::disk('public')->exists($key)) {
            return $key;
        }

        return '';
    }
}
