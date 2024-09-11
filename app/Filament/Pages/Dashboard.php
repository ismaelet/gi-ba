<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard {

	protected static ?string $navigationIcon = 'icon-dashboard';

	public function getColumns(): int|string|array {
		return 12;
	}

	public function getWidgets(): array {
		return [];
	}
}
