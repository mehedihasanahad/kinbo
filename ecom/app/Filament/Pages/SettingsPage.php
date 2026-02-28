<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

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

    public function mount(): void
    {
        $this->general = [
            'site_name'        => Setting::get('site_name', config('app.name')),
            'site_tagline'     => Setting::get('site_tagline', ''),
            'site_description' => Setting::get('site_description', ''),
            'default_locale'   => Setting::get('default_locale', 'en'),
            'currency'         => Setting::get('currency', 'BDT'),
            'currency_symbol'  => Setting::get('currency_symbol', '৳'),
        ];

        $this->contact = [
            'contact_email'   => Setting::get('contact_email', ''),
            'contact_phone'   => Setting::get('contact_phone', ''),
            'contact_address' => Setting::get('contact_address', ''),
            'contact_city'    => Setting::get('contact_city', ''),
        ];

        $this->payment = [
            'bkash_merchant_number'  => Setting::get('bkash_merchant_number', env('BKASH_MERCHANT_NUMBER', '')),
            'bkash_merchant_name'    => Setting::get('bkash_merchant_name', env('BKASH_MERCHANT_NAME', '')),
            'nagad_merchant_number'  => Setting::get('nagad_merchant_number', env('NAGAD_MERCHANT_NUMBER', '')),
            'nagad_merchant_name'    => Setting::get('nagad_merchant_name', env('NAGAD_MERCHANT_NAME', '')),
            'cod_enabled'            => Setting::get('cod_enabled', '1'),
            'free_shipping_above'    => Setting::get('free_shipping_above', ''),
        ];

        $this->social = [
            'facebook_url'  => Setting::get('facebook_url', ''),
            'instagram_url' => Setting::get('instagram_url', ''),
            'youtube_url'   => Setting::get('youtube_url', ''),
            'twitter_url'   => Setting::get('twitter_url', ''),
        ];
    }

    protected function getForms(): array
    {
        return ['generalForm', 'contactForm', 'paymentForm', 'socialForm'];
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
}
