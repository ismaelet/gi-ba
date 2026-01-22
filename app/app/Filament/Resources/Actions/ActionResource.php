<?php

namespace App\Filament\Resources\Actions;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Actions\Pages\ListActions;
use App\Filament\Resources\ActionResource\Pages;
use App\Filament\Resources\ActionResource\RelationManagers;
use App\Models\Action;
use Filament\Forms;
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

	protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clock';

	public static function canCreate(): bool {
		return false;
	}

	public static function form(Schema $schema): Schema {
		return $schema
			->components([
				//
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				TextColumn::make('user.username')
					->label('Utente')
					->searchable()
					->sortable(),
				TextColumn::make('action')
					->label('Azione')
					->searchable()
					->sortable(),
				TextColumn::make('created_at')
					->label('Data')
					->searchable()
					->sortable(),
			])
			->filters([
				//
			])
			->recordActions([])
			->toolbarActions([
				BulkActionGroup::make([
					DeleteBulkAction::make(),
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
			'index' => ListActions::route('/'),
		];
	}
}
