<?php

$public = sysDB('static') ?: \Legacy\System\Classes\DB::find('value FROM config WHERE name=?', ['public']);

if (!$public && !$user->admin) legacy_redirect('/offline');
