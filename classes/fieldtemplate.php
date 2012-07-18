<?php

namespace Datagenerator;

/**
 * Holds info about the different field types we know about
 **/
class FieldTemplate
{
	public static $templates = [
		'name' => [
			'display' => 'Name',
			'type' => 'template',
			'subtypes' => [
				'forename' => [
					'display' => 'Forename',
					'value' => '{forename}',
				],
				'surname' => [
					'display' => 'Surname',
					'value' => '{surname}',
				],
				'name' => [
					'display' => 'Forename Surname',
					'value' => '{forename} {surname}',
				],
				'fullname' => [
					'display' => 'Forename X. Surname',
					'value' => '{forename} {initial}. {surname}',
				],
			],
		],
		'date' => [
			'display' => 'Date',
			'type' => 'strftime',
			'extra_fields' => [
				'from' => 'date',
				'to' => 'date',
			],
			'subtypes' => [
				'%d/%m/%Y' => [
					'display' => '{name}',
					'value' => '%d/%m/%Y',
				],
			],
		],
	];

	protected $_type;
	protected $_subtype;
	protected $_options;

	public static function type_options() {
		$arr = [];

		foreach (self::$templates as $name => $config) {
			$arr[$name] = $config['display'];
		}

		return $arr;
	}
	/**
	 * Create a new instance based on this main type.
	 **/
	public function __construct($type)
	{
		$this->_type = $type;
	}

	public function subtype_options() {
		$arr = [];
		$config = self::$templates[$this->_type];

		foreach ($config['subtypes'] as $name => $st_config) {
			$arr[$name] = $st_config['display'];
		}

		return $arr;
	}

	public function subtype($st = null) {
		if (null === $st) {
			return $this->_subtype;
		}

		if ($st === false) {
			$this->_subtype = '';
		}
		else {
			if (isset(self::$templates[$this->_type]['subtypes'][$st]))
				$this->_subtype = $st;
			else
				$this->_subtype = null;
		}
	}

	public function value() {
		if (! $this->_subtype) {
			return null;
		}

		return self::$templates[$this->_type]['subtypes'][$this->_subtype]['value'];
	}
}

foreach (FieldTemplate::$templates as $name => &$config) {
	foreach ($config['subtypes'] as $key => &$st_config) {
		$st_config['display'] = str_replace('{name}', $key, $st_config['display']);

		if ($name == 'date') {
			$st_config['display'] = strftime($st_config['display']);
		}
	}
}
