<?php

namespace Datagenerator;

/**
 * Generate data based on field templates
 **/
class DataGenerator {
	protected static $_lipsum;

	public static function generate($templates, $num_records) {
		\Config::load('datagenerator');

		$data = [];
		for ($i = 0; $i < $num_records; $i++) {
			$datum = [];
			foreach ($templates as $field => $template) {
				$type = $template->type();
				$value = $template->value();

				$datum[$field] = static::{"_make_$type"}($value);
			}

			$data[] = $datum;
		}

		return $data;
	}

	public static function lipsum($amount = 1, $what = 'paras', $start = 0) {
		if (! static::$_lipsum or !isset(static::$_lipsum[$what]) or count(static::$_lipsum[$what]) < $amount) {
			$n = $amount * 5;

			$lipsum = simplexml_load_file("http://www.lipsum.com/feed/xml?amount=$n&what=$what&start=$start")->lipsum;
			$split = $what == 'bytes' ? ''
			       : $what == 'words' ? ' '
				   : 					"\n";
			if ($what == 'words') {
				$lipsum = preg_replace('/[[:punct:]]/', '', $lipsum);
			}
			$lipsum = explode($split, $lipsum);
			static::$_lipsum[$what] = $lipsum;
		}

		return array_splice(static::$_lipsum[$what], 0, $amount);
	}

	public static function parse_char_ranges($pattern) {
		$str = preg_replace_callback(
			'/.-./',
			function($thing) {
				$things = explode('-',  $thing[0]);
				return implode('', array_map(function($_) { return chr($_); }, range(ord($things[0]), ord($things[1]))));
			},
			$pattern
		);

		return $str;
	}

	protected static function _make_name($template) {
		return static::_make_string($template);
	}

	protected static function _make_string($template) {
		preg_match_all('/\{([^}]+)\}/', $template, $matches);

		foreach ($matches[1] as $token) {
			$parts = explode(':',$token);

			$type = array_shift($parts);

			# null means not-a-maybe pattern
			$maybe = static::_check_maybe($type);
			if ($maybe === false) {
				$template = substr_replace($template, "", strpos($template, "{{$token}}"), strlen($token) + 2);
				continue;
			}
			if ($maybe === true) {
				$type = array_shift($parts);
			}

			if (is_numeric($type)) {
				if (! count($parts)) {
					throw new \UnexpectedValueException("Range template requires two parameters; only got one - $type");
				}

				$min = $type;
				$max = $parts[0];

				# 2:3 -> 10 ^ (2 - 1) = 10; 10 ^ 3 = 1000 - 1 = 999 -> 10 ... 999
				$choice = rand(pow(10, $min - 1), pow(10, $max) - 1);
			}
			elseif ($type == 'initial') {
				$choice = chr(rand(65, 25+65));
			}
			elseif ($type == 'lipsum') {
				$concat = $parts ? array_shift($parts) : ' ';
				$what = array_shift($parts) ?: 'words';
				$min = array_shift($parts) ?: 1;
				$max = array_shift($parts) ?: $min;

				if ($concat == '\n') $concat = "\n";

				$choice = join($concat, static::lipsum(rand($min,$max), $what));
			}
			elseif ($type == 'domain') {
				$choice = join('.', static::lipsum(rand(1,2), 'words'));
			}
			elseif ($type == 'word') {
				$words = file(\Config::get('datagenerator.dict'), FILE_IGNORE_NEW_LINES);
				$choice = $words[array_rand($words)];
			}
			elseif ($type == 'rand') {
				$min = array_shift($parts) ?: 8;
				$max = array_shift($parts) ?: $min;
				$pattern = array_shift($parts) ?: 'a-zA-Z0-9';

				$num = rand($min,$max);
				$chars = str_split(static::parse_char_ranges($pattern));

				$str = '';
				for ($i = 0; $i < $num; ++$i) {
					$str .= $chars[array_rand($chars)];
				}

				$choice = $str;
			}
			else {
				$choice = \DB::select('value')
					->from('string_template_values')
					->where(\DB::expr('FIND_IN_SET(' . \DB::quote($type) . ', type)'), '!=', 0)
					->order_by(\DB::expr('RAND()'))
					->limit(1)
					->execute('datagenerator')
					->as_array(null, 'value');

				if (!$choice) {
					throw new \Exception("Couldn't find any string values for $type");
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
		preg_match_all('/\{(\?(?:=\d+:)?)?(\d+)(?::(\d+))?\}/', $format, $matches);

		foreach ($matches[2] as $i => $token) {
			# null means not-a-maybe pattern
			if ($matches[1][0]) {
				$maybe = static::_check_maybe($matches[1][0]);
				if ($maybe === false) {
					$format = substr_replace($format, '', strpos($format, "{{$format}}"), strlen($format));
					continue;
				}
			}

			$min = $matches[2][$i];

			if ($matches[3][$i]) {
				$max = $matches[3][$i];
			}
			else {
				$max = $min;
				$min = 0;
			}

			$num = '';
			for ($i = $min; $i < $max; $i++) {
				$num .= rand(0,9);
			}
			$format = substr_replace($format, $num, strpos($format, "{{$format}}"), strlen($format));
		}

		return $format;
	}

	protected static function _check_maybe($format) {
		if(! preg_match('/^\?(?:=(\d+))?/', $format, $matches)) {
			return;
		}

		$rand = rand(1, \Arr::get($matches, 1, 2));
		if ((int) $rand === 1) {
			return true;
		}

		return false;
	}
}
