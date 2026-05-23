<?php

namespace App\Filament\Pages;

use App\Jobs\SendNewsletterJob;
use App\Models\Subscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class NewsletterPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Newsletter';
    protected static ?string $title           = 'Send Newsletter';
    protected static ?int    $navigationSort  = 4;

    protected static string $view = 'filament.pages.newsletter-page';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->hasPermission('manage_newsletter'));
    }

    public string $subject       = '';
    public string $body          = '';
    public string $recipientType = 'all';  // 'all' | 'specific'
    public array  $subscriberIds = [];

    #[Computed]
    public function totalActive(): int
    {
        return Subscriber::active()->count();
    }

    #[Computed]
    public function recipientCount(): int
    {
        if ($this->recipientType === 'specific') {
            return count($this->subscriberIds);
        }
        return Subscriber::active()->count();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Compose Newsletter')
                ->schema([
                    Forms\Components\Select::make('recipientType')
                        ->label('Send to')
                        ->options([
                            'all'      => 'All active subscribers',
                            'specific' => 'Choose specific subscribers',
                        ])
                        ->default('all')
                        ->live()
                        ->required(),

                    Forms\Components\Select::make('subscriberIds')
                        ->label('Select Subscribers')
                        ->multiple()
                        ->searchable()
                        ->options(fn () => Subscriber::active()->orderBy('email')->pluck('email', 'id')->toArray())
                        ->visible(fn (Forms\Get $get) => $get('recipientType') === 'specific')
                        ->required(fn (Forms\Get $get) => $get('recipientType') === 'specific')
                        ->helperText('Search and select one or more subscribers.'),

                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(200)
                        ->placeholder('Your email subject line'),

                    Forms\Components\RichEditor::make('body')
                        ->required()
                        ->label('Email Body')
                        ->helperText('HTML is supported. An unsubscribe link is automatically appended.'),
                ]),
        ])->statePath(''); // bind directly to component properties
    }

    public function send(): void
    {
        $rules = [
            'subject' => 'required|string|max:200',
            'body'    => 'required|string',
        ];

        if ($this->recipientType === 'specific') {
            $rules['subscriberIds']   = 'required|array|min:1';
            $rules['subscriberIds.*'] = 'exists:subscribers,id';
        }

        $this->validate($rules);

        $count = $this->recipientCount;

        if ($count === 0) {
            Notification::make()
                ->title('No active subscribers found.')
                ->warning()
                ->send();
            return;
        }

        dispatch(new SendNewsletterJob(
            subject:       $this->subject,
            body:          $this->body,
            subscriberIds: $this->recipientType === 'specific' ? $this->subscriberIds : null,
        ));

        Notification::make()
            ->title("Newsletter queued for {$count} subscriber(s).")
            ->success()
            ->send();

        $this->subject       = '';
        $this->body          = '';
        $this->recipientType = 'all';
        $this->subscriberIds = [];
    }

    public function deleteSubscriber(int $id): void
    {
        Subscriber::findOrFail($id)->delete();

        Notification::make()
            ->title('Subscriber deleted.')
            ->success()
            ->send();
    }
}
