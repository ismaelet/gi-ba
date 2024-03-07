<?php

$public = sysDB('static') ?: DB::find('value FROM variables WHERE name=?', ['public']);

if ($public) redirect('/');