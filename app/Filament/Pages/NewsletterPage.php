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

    public string $subject = '';
    public string $body    = '';
    public string $locale  = '';   // empty = all

    #[Computed]
    public function totalActive(): int
    {
        return Subscriber::active()->count();
    }

    #[Computed]
    public function recipientCount(): int
    {
        $q = Subscriber::active();
        if ($this->locale) {
            $q->where('locale', $this->locale);
        }
        return $q->count();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Compose Newsletter')
                ->schema([
                    Forms\Components\Select::make('locale')
                        ->label('Send to')
                        ->options([
                            ''   => 'All subscribers',
                            'en' => 'English subscribers only',
                        ])
                        ->default('')
                        ->live()
                        ->helperText(fn () => 'Recipients: ' . $this->recipientCount),

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
        $this->validate([
            'subject' => 'required|string|max:200',
            'body'    => 'required|string',
        ]);

        $count = $this->recipientCount;

        if ($count === 0) {
            Notification::make()
                ->title('No active subscribers found for the selected locale.')
                ->warning()
                ->send();
            return;
        }

        dispatch(new SendNewsletterJob(
            subject: $this->subject,
            body:    $this->body,
            locale:  $this->locale ?: null,
        ));

        Notification::make()
            ->title("Newsletter queued for {$count} subscriber(s).")
            ->success()
            ->send();

        $this->subject = '';
        $this->body    = '';
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
