<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->requiresConfirmation()
            ->modalHeading("Konfirmasi Simpan")
            ->modalDescription("Apakah Anda yakin ingin menyimpan data ini?")
            ->modalSubmitActionLabel("Ya, Simpan");
    }
}
