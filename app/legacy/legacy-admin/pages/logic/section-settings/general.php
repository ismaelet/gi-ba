<?php

$public = \Legacy\System\Classes\DB::find('value FROM variables WHERE name=?', ['public']);

if (isset($_POST['public'])) {

	foreach ($_POST['cbx_public'] as $public);

	\Legacy\System\Classes\DB::update('variables SET value=? WHERE name=?', [$public, 'public']);
}
