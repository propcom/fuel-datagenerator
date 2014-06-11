<?php

namespace Datagenerator;

/**
 * Holds info about the different field types we know about
 **/
class FieldTemplate
{
	public static $templates;

	protected $_type;
	protected $_preset;
	protected $_value;

	public static function _init() {
		\Config::load('datagenerator',true);
		static::$templates = \Config::get('datagenerator.templates');

		foreach (static::$templates as $name => &$config) {
			foreach ($config['presets'] as $key => &$st_config) {
				$st_config['display'] = str_replace('{name}', $key, $st_config['display']);

				if ($name == 'date') {
					$st_config['display'] = strftime($st_config['display']);
				}
			}
		}
	}

	public static function type_options() {
		$arr = [];

		foreach (static::$templates as $name => $config) {
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

	public function preset_options() {
		$arr = [];
		$config = static::$templates[$this->_type];

		foreach ($config['presets'] as $name => $st_config) {
			$arr[$name] = $st_config['display'];
		}

		return $arr;
	}

	public function type() {
		return $this->_type;
	}

	public function preset($st = null) {
		if (null === $st) {
			return $this->_preset;
		}

		if ($st === false) {
			$this->_preset = '';
		}
		else {
			if (isset(static::$templates[$this->_type]['presets'][$st]))
				$this->_preset = $st;
			else
				$this->_preset = null;
		}
	}

	public function value($val = null) {
		if ($val) {
			$this->_value = $val;
			return;
		}

		if (! $this->_value) {
			if (! $this->_preset) {
				return null;
			}

			$this->_value = static::$templates[$this->_type]['presets'][$this->_preset]['value'];
		}

		return $this->_value;
	}
}
