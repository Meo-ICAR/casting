<?php

namespace App\Filament\Pages\Auth;


use Filament\Forms\Form;
use Filament\Auth\Pages\Register;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CustomRegister extends Register
{
   public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                /*
                 Select::make('role')
                    ->label('Iscriviti come')
                    ->options([
                        'actor' => 'Attore/Attrice',
                        'director' => 'Regista',
                        'service' => 'Professionista',
                        'location' => 'Locatario',
                    ])
                    ->default('actor') // Default role
                    ->required()
                    ->in(['actor', 'director', 'company'])
                    ->native(false)
                    ->live(),
*/
                // Sezione GDPR
                Section::make('Privacy & Consensi')
                    ->description('Per proseguire è necessario accettare i termini del servizio.')
                    ->schema([
                        Checkbox::make('data_processing_consent')
                            ->label(fn () => new HtmlString('Accetto i <a href="/terms" class="text-primary-600 underline" target="_blank">Termini e Condizioni</a>'))
                            ->required() // Rende il campo obbligatorio
                            ->rules(['accepted']),

                        Checkbox::make('marketing_consent')
                            ->label(fn () => new HtmlString('Ho letto l\'<a href="/privacy" class="text-primary-600 underline" target="_blank">Informativa Privacy</a>'))
                            ->required()
                            ->rules(['accepted']),

                        Checkbox::make('newsletter_subscription')
                            ->label('Acconsento all\'invio di materiale informativo (opzionale)'),
                    ]),
            ]);
    }
}
