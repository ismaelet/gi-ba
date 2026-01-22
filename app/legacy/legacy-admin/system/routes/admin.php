<?php

// sections
$sections = \Legacy\System\Classes\DB::select('slug, category FROM admin_sections');

while ($section = $sections->fetch_assoc()) {
	\Legacy\System\Classes\Route::add('/' . $section['category'] . '/' . $section['slug'], function ($path) {
		global $id;

		$id = $_GET['id'] ?? null;

		// fetch section data
		$uriParts = explode('/', explode('legacy-admin/', $path)[1], 2);
		$section = \Legacy\System\Classes\DB::row('* FROM admin_sections WHERE slug=? AND category=?', [$uriParts[1], $uriParts[0]]);

		// setup view
		\Legacy\System\Classes\View::setPage('section-' . $section['category'] . '/' . $section['slug'])
			->setTitle($section['name'])
			->setTemplate(in_array($section['template'], ['admin', 'void']) ? $section['template'] : 'admin-' . $section['template'])
			->addMiddlewares(['admin'])
			->show();
	});
}
