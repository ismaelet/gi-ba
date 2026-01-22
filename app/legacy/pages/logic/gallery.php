<?php

//setup variables
$this->set('h_theme', true, 'floating');

//fetch collections
$collections = \Legacy\System\Classes\DB::select('* FROM collections ORDER BY sort ASC, id ASC');

//store variables
$this->set('collections', $collections);
