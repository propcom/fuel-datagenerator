<?php

namespace Datagenerator;

class Format extends \Format
{
	public function to_sql($data = null) {
		$query = \DB::insert(\Config::get('format.sql.table_name', 'some_table'));

		if ($data === null) {
			$data = $this->_data;
		}

		if (! \Arr::is_multi($data)) {
			$data = [$data];
		}

		if (\Arr::is_assoc($data[0])) {
			$cols = array_keys($data[0]);
		}
		else {
			$cols = array_map(function($a) {
				return "col$a";
			}, array_keys($data[0]));
		}

		$query->columns($cols);

		foreach ($data as $row) {
			$query->values(array_values($row));
		}

		return $query->compile();
	}
}
