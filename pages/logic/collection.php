<?php

//setup theme
$this->set('h_theme', true, 'floating');
$this->set('h_theme', 'dark', 'color');

//fetch collection
$id = $_GET['id'] ?? false;

$collection = DB::row('* FROM collections WHERE id=?', [$id]);

if (!$collection) redirect('/gallery');

//fetch images
$images = DB::select('* FROM images WHERE hidden=0 AND collection=? ORDER BY id DESC', [$id]);

//store variables
$this->set('collection', $collection);
$this->set('images', $images);