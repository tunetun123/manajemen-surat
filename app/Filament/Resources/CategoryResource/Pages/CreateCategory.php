<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

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
