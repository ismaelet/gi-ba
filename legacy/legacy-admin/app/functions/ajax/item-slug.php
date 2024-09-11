<?php

$name = $_GET['name'];
$response['slug'] = generate_item_slug($name);

echo json_encode($response);
