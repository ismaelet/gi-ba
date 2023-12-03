<?php

$public = sysDB('static') ?: DB::find('value FROM config WHERE name=?', ['public']);

if (!$public && !$user->admin) redirect('/offline');
