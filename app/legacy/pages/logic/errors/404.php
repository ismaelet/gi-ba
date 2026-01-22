<?php

//setup page
$this->title = 'Errore 404';
$this->template = 'void';

//store error
$url = $_SERVER['REQUEST_URI'] ?? false;
$src = $_SERVER['HTTP_REFERER'] ?? '';

if ($url) $url = substr($url, 0, strpos($url, '?'));

if ($url && \Legacy\System\Classes\DB::table('errors') && \Legacy\System\Classes\DB::table('errors_whitelist')) {

	if (!\Legacy\System\Classes\DB::exists('errors_whitelist WHERE url=?', [$url])) \Legacy\System\Classes\DB::add('errors (name, details, source) VALUES (?, ?, ?)', ['404', $url, $src]);
}
