<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionResource\Pages;
use App\Filament\Resources\ActionResource\RelationManagers;
use App\Models\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActionResource extends Resource {

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Azione';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Storico';

	protected static ?string $model = Action::class;

	protected static ?string $navigationIcon = 'heroicon-o-clock';

	public static function form(Form $form): Form {
		return $form
			->schema([
				//
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				Tables\Columns\TextColumn::make('user.username')
					->label('Utente')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('action')
					->label('Azione')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('created_at')
					->label('Data')
					->searchable()
					->sortable(),
			])
			->filters([
				//
			])
			->actions([])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make(),
				]),
			])
			->defaultSort('created_at', 'desc')
			->paginated([10, 25, 50, 100, 'all'])
			->defaultPaginationPageOption(100)
		;
	}

	public static function getRelations(): array {
		return [
			//
		];
	}

	public static function getPages(): array {
		return [
			'index' => Pages\ListActions::route('/'),
		];
	}
}
