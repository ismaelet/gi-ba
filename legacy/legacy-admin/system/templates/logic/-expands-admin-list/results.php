<?php

// setup variables
$__listResults = [];
$asd = [];
$fields = \Legacy\System\Classes\View::get('fields');

// generate the results
while ($row = $res->fetch_assoc()) {

	$result = [];

	foreach ($fields as $key => $field) {

		// setup variables
		$entry = [];

		$field['type'] = $field['type'] ?? 'show';
		$field['name'] = $field['name'] ?? false;
		$field['max'] = $field['max'] ?? false;
		$field['parse'] = $field['parse'] ?? false;

		// get value
		if (isset($field['value'])) $entry['value'] = $field['value'];
		else if ($field['type'] == 'find') $entry['value'] = \Legacy\System\Classes\DB::find($field['find'], [$row[$field['name']]]) ?: '-';
		else if ($field['name'] && isset($row[$field['name']])) $entry['value'] = strlen($row[$field['name']]) ? $row[$field['name']] : '-';

		// adjust value
		if ($field['max'] && strlen($entry['value']) > $field['max']) $entry['value'] = substr($entry['value'], 0, $field['max'] - 3) . '...';
		if (isset($field['switch'])) $entry['value'] = $entry['value'] ? $field['switch'][0] : $field['switch'][1];

		// parse value
		if ($field['parse']) {

			if ($field['parse'] == 'float') $entry['value'] = format($entry['value']);
			else if ($field['parse'] == 'date-day') $entry['value'] = date('Y-m-d', strtotime($entry['value']));
			else if ($field['parse'] == 'date-full') $entry['value'] = date('Y-m-d H:i:s', strtotime($entry['value']));
		}

		// additional code
		if (function_exists('_expand_results')) _expand_results($entry, $field, $key, $row);

		// store entry
		$entry['type'] = $field['type'];
		$entry['name'] = $field['name'];
		$entry['value'] = $entry['value'] ?? false;
		$entry['maxlenght'] = $field['maxlenght'] ?? false;
		$entry['overflow'] = $field['overflow'] ?? false;
		$entry['max-size'] = $field['max-size'] ?? false;

		if ($field['type'] == 'btn') include('buttons.php');

		$result[] = $entry;
	}

	// index input
	$result[] = ['type' => 'hidden', 'name' => $index, 'value' => $row[$index]];

	// store data
	$__listResults[] = $result;
}

unset($fields);
