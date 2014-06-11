<?php

namespace Datagenerator;

class Controller_Home extends \Controller_Template
{
	public $template = 'template';

	public function before()
	{
		parent::before();

		\Asset::add_path('assets/datagenerator', ['js', 'css']);
	}

	public function action_index()
	{
		$this->template->content = \View::forge('form');
		$this->template->content->set_global('type_options', FieldTemplate::type_options());

		$f_template = new FieldTemplate(key(FieldTemplate::type_options()));

		$this->template->content->set_global('preset_options', $f_template->preset_options());
		$this->template->content->set_global('preset', null);
		$this->template->content->set_global('value', null);

		$this->template->content->set_global('fs', \Fieldset::forge(), false);
	}

	public function action_generate()
	{
		$fields = \Input::get('field');
		$templates = [];

		foreach ($fields as $field)
		{
			if (! $field['name']) continue;

			$t = new FieldTemplate($field['type']);
			$t->value($field['value']);
			$templates[$field['name']] = $t;
		}

		$data = DataGenerator::generate($templates, \Input::get('num_records'));

		$format = Format::forge();
		$formatted = $format->{"to_" . \Input::get('format')}($data);

		// TODO: other formats
		return \Response::forge($formatted, 200, [
			'Content-Type' => \Config::get('format.mime_types.' . \Input::get('format'), 'text/plain'),
		]);
	}

	public function action_tablerow()
	{
		$name = \Input::post('name');
		$coltype = \Input::post('type');
		$preset = \Input::post('preset');
		$value = \Input::post('value');
		$template = new FieldTemplate($coltype);

		if ($preset)
		{
			$template->preset($preset);
			$value = $template->value();
		}

		$row = \View::forge('form_row');

		$row->i = \Input::post('i');

		$row->name = $name;

		$row->type = $coltype;
		$row->type_options = FieldTemplate::type_options();

		$row->preset_options = $template->preset_options();
		$row->preset = $preset;

		$row->value = $value;

		$row->set('fs', \Fieldset::forge(), false);

		// TODO: extra data

		return \Response::forge($row);
	}
}
