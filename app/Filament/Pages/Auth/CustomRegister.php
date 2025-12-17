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

                // Sezione GDPR
                Section::make('Privacy & Consensi')
                    ->description('Per proseguire è necessario accettare i termini del servizio.')
                    ->schema([
                        Checkbox::make('terms')
                            ->label(fn () => new HtmlString('Accetto i <a href="/terms" class="text-primary-600 underline" target="_blank">Termini e Condizioni</a>'))
                            ->required() // Rende il campo obbligatorio
                            ->rules(['accepted']),

                        Checkbox::make('privacy')
                            ->label(fn () => new HtmlString('Ho letto l\'<a href="/privacy" class="text-primary-600 underline" target="_blank">Informativa Privacy</a>'))
                            ->required()
                            ->rules(['accepted']),

                        Checkbox::make('marketing')
                            ->label('Acconsento all\'invio di materiale informativo (opzionale)'),
                    ]),
            ]);
    }
}
