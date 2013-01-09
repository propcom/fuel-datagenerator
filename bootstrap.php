<?php

Config::set('module_paths', Config::get('module_paths') + [ __DIR__ . '/modules/' ]);
Autoloader::add_classes([
	'Datagenerator\DataGenerator' => __DIR__.'/classes/datagenerator.php',
	'Datagenerator\FieldTemplate' => __DIR__.'/classes/fieldtemplate.php',
]);
