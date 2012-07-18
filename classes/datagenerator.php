<?php

namespace Datagenerator;

/**
 * Generate data based on field templates
 **/
class DataGenerator {
	public static function generate($templates, $num_records) {
		$data = [];
		for ($i = 0; $i < $num_records; $i++) {
			$datum = [];
			foreach ($templates as $field => $template) {
				$type = $template->type();
				$value = $template->value();

				$datum[$field] = self::{"_make_$type"}($value);
			}

			$data[] = $datum;
		}

		return $data;
	}

	protected static function _make_name($template) {
		preg_match_all('/\{(\w+)\}/', $template, $matches);

		foreach ($matches[1] as $token) {
			if ($token == 'initial') {
				$choice = chr(rand(65, 25+65));
			}
			else {
				$choice = \DB::select('name')
					->from('names')
					->where(\DB::expr('FIND_IN_SET(' . \DB::quote($token) . ', type)'), '!=', 0)
					->order_by(\DB::expr('RAND()'))
					->limit(1)
					->execute()
					->as_array(null, 'name')[0];
			}

			$template = substr_replace($template, $choice, strpos($template, "{{$token}}"), strlen($token) + 2);
		}

		return $template;
	}

	protected static function _make_date($format, $config = null) {
		$date = rand(0, time());

		// TODO: min/max from $config
		return strftime($format, $date);
	}

	protected static function _make_enum($options, $config = null) {
		// TODO: one or many from config.
		$options = explode('|', $options);

		return $options[array_rand($options)];
	}

	protected static function _make_number($format) {
		preg_match_all('/\{(\d+)\}/', $format, $matches);

		foreach ($matches[1] as $token) {
			$num = '';
			for ($i = 0; $i < $token; $i++) {
				$num .= rand(0,9);
			}
			$format = substr_replace($format, $num, strpos($format, "{{$token}}"), strlen($token) + 2);
		}

		return $format;
	}
}
