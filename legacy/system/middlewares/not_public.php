<?php

$public = sysDB('static') ?: \Legacy\System\Classes\DB::find('value FROM variables WHERE name=?', ['public']);

if ($public) legacy_redirect('/');
