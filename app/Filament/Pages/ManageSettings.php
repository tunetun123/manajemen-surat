<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'fas-cog';
    protected static ?string $navigationLabel = 'Pengaturan Aplikasi';
    protected static ?string $title = 'Pengaturan Aplikasi';

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::first();
        if ($setting) {
            $this->form->fill($setting->toArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('app_name')
                    ->label('Nama Aplikasi')
                    ->required(),
                TextInput::make('institution_name')
                    ->label('Nama Instansi')
                    ->required(),
                FileUpload::make('logo_path')
                    ->label('Logo Instansi')
                    ->image()
                    ->directory('settings'),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $setting = Setting::first();
        if ($setting) {
            $setting->update($this->form->getState());
        } else {
            Setting::create($this->form->getState());
        }
        
        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
