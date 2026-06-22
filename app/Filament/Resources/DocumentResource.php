<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'fas-file-contract';
    protected static ?string $modelLabel = 'Dokumen';
    protected static ?string $pluralModelLabel = 'Dokumen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Judul')
                    ->required(),
                Forms\Components\TextInput::make('surat_no')->label('Nomor Surat'),
                Forms\Components\Select::make('category_id')->label('Kategori')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Select::make('document_type_id')->label('Jenis Dokumen')
                    ->relationship('documentType', 'name')
                    ->required(),
                Forms\Components\FileUpload::make('file_path')->label('Berkas Dokumen')
                    ->directory('documents')
                    ->required(),
                Forms\Components\DatePicker::make('upload_date')->label('Tanggal Upload')
                    ->default(now()),
                Forms\Components\Textarea::make('description')->label('Deskripsi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('surat_no')->label('Nomor Surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('documentType.name')->label('Jenis Dokumen')
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_path')->label('Berkas')
                    ->formatStateUsing(fn() => 'Unduh File')
                    ->color('primary')
                    ->url(fn($record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('upload_date')->label('Tanggal Upload')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
