<?php

class DB {

	//generic query
	static function run($query, $values) {

		global $con;

		if ($values) $values = (array) $values;

		//prepare the query
		$stmt = $con->prepare($query);

		//execute the query
		if ($stmt) {

			//get types
			$types = '';

			if (!empty($values)) {

				foreach ($values as $value) {

					if (strlen($value) > 1 && strval($value)[0] == 0) $types .= 's';
					else if (is_int($value)) $types .= 'i';
					else if (is_numeric($value)) $types .= 'd';
					else $types .= 's';

				}

				$stmt->bind_param($types, ...$values);

			}

			$stmt->execute();
			$stmt->store_result();

			return new Query($stmt);

		} else {

			/*$txt_val = $values ? implode(',', $values) : 'none';
		
			write_log('Page: '.$_SERVER['REQUEST_URI'].' - Query: '.$query. ' - Variabiles: '.$txt_val);*/

			return false;
		}
	}

	//select several rows
	static function select($query, $values = false) {

		$query = 'SELECT '.$query;
		$res = self::run($query, $values);

		if ($res) return $res->get_result();
		else return false;
	}


	//select a single row
	static function row($query, $values = false) {

		$query = $query.' LIMIT 1';

		$res = self::select($query, $values);

		return  $res->fetch_assoc();
	}

	//select a specific value
	static function find($query, $values = false) {

		$what = substr($query, 0, strpos($query, ' FROM'));

		$row = self::row($query, $values);

		if ($row) return $row[$what];
		else return false;
	}

	//get a count of entries
	static function count($query, $values = false) {

		$query = 'COUNT(*) AS num FROM '.$query;

		return self::row($query, $values)['num'];
	}

	//get a sum of values
	static function sum($query, $values = false) {

		$what = substr($query, 0, strpos($query, ' FROM'));
		$query = substr($query, strpos($query, 'FROM ') + 5);

		$query = 'SUM('.$what.') AS tot FROM '.$query;

		return self::row($query, $values)['tot'];
	}

	//add an element
	static function add($query, $values = false) {

		global $con;

		$query = 'INSERT INTO '.$query;

		if (self::run($query, $values)) return $con->insert_id;
		else return false;
	}

	//update an element
	static function update($query, $values = false) {

		$query = 'UPDATE '.$query;

		return self::run($query, $values);
	}

	//delete an element
	static function delete($query, $values = false) {

		$query = 'DELETE FROM '.$query;

		return self::run($query, $values);
	}

	//check if an element exists 
	static function exists($query, $values = false) {

		$query = '1 FROM '.$query.' LIMIT 1';

		$res = self::select($query, $values);

		return $res->num_rows;
	}

	//check if a table exists
	static function table($tbl) {

		global $con;

		$query = "SHOW TABLES LIKE '".$tbl."'";

		return $con->query($query)->num_rows;
	}
}