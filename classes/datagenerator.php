<?php

namespace Datagenerator;

/**
 * Generate data based on field templates
 **/
class DataGenerator {
	protected static $_lipsum;

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

	public static function lipsum($amount = 1, $what = 'paras', $start = 0) {
		if (! self::$_lipsum or !isset(self::$_lipsum[$what]) or count(self::$_lipsum[$what]) < $amount) {
			$n = $amount * 5;

			$lipsum = simplexml_load_file("http://www.lipsum.com/feed/xml?amount=$n&what=$what&start=$start")->lipsum;
			$split = $what == 'bytes' ? ''
			       : $what == 'words' ? ' '
				   : 					"\n";
			if ($what == 'words') {
				$lipsum = preg_replace('/[[:punct:]]/', '', $lipsum);
			}
			$lipsum = explode($split, $lipsum);
			self::$_lipsum[$what] = $lipsum;
		}

		return array_splice(self::$_lipsum[$what], 0, $amount);
	}

	protected static function _make_name($template) {
		return self::_make_string($template);
	}

	protected static function _make_string($template) {
		preg_match_all('/\{(\w+)\}/', $template, $matches);

		foreach ($matches[1] as $token) {
			if ($token == 'initial') {
				$choice = chr(rand(65, 25+65));
			}
			if ($token == 'lipsum') {
				$choice = join('.', self::lipsum(rand(1,3), 'words'));
			}
			if ($token == 'domain') {
				$choice = join('.', self::lipsum(rand(1,2), 'words'));
			}
			else {
				$choice = \DB::select('value')
					->from('string_template_values')
					->where(\DB::expr('FIND_IN_SET(' . \DB::quote($token) . ', type)'), '!=', 0)
					->order_by(\DB::expr('RAND()'))
					->limit(1)
					->execute('datagenerator')
					->as_array(null, 'value');

				if (!$choice) {
					throw new \Exception("Couldn't find any string values for $token");
				}

				$choice = $choice[0];
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
