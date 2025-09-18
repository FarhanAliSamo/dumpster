<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;

/**
 * @var \App\Models\User $user
 */

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.pages.profile';
    protected static ?string $title = 'My Profile';
    protected static bool $shouldRegisterNavigation = false;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public function mount(): void
    {
        $user = auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->label('Name'),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->label('Email')
                        ->disabled(),

                    TextInput::make('password')
                        ->password()
                        ->label('New Password')
                        ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(fn($state) => filled($state))
                        ->confirmed(),

                    TextInput::make('password_confirmation')
                        ->password()
                        ->label('Confirm Password'),
                ]),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();
        $user->update($data);

        
    Notification::make()
        ->title('Profile updated successfully!')
        ->success()
        ->send();
    }
}
