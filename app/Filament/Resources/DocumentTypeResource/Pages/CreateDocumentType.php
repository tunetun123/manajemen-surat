<?php

namespace App\Filament\Resources\DocumentTypeResource\Pages;

use App\Filament\Resources\DocumentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentType extends CreateRecord
{
    protected static string $resource = DocumentTypeResource::class;

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
