<?php

return [
	'dict' => '/usr/share/dict/words',
	'templates' => [
		'name' => [
			'display' => 'Name',
			'presets' => [
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
			'extra_fields' => [
				'from' => 'date',
				'to' => 'date',
			],
			'presets' => [
				'%d/%m/%Y' => [
					'display' => '{name}',
					'value' => '%d/%m/%Y',
				],
			],
		],
		'enum' => [
			'display' => 'Enum or set',
			'type' => 'enum',
			'presets' => [],
		],
		'number' => [
			'display' => 'Numeric data',
			'presets' => [
				'uk_intl' => [
					'display' => 'UK International',
					'value' => '0044{10}'
				],
				'uk_nat' => [
					'display' => 'UK National',
					'value' => '(0{3}) {7}',
				],
			],
		],
		'string' => [
			'display' => 'String data',
			'presets' => [
				'email1' => [
					'display' => 'Email address',
					'value' => '{forename}{surname}@{domain}',
				],
				'postcode' => [
					'display' => 'UK Postcode',
					'value' => '{rand:1:2:A-Z}{1:2} {1:1}{rand:2:2:A-Z}',
				],
				'zip' => [
					'display' => 'Zip code',
					'value' => '{1:9}{rand:4:4:0-9}',
				],
			]
		]
	]
];
