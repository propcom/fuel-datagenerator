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

		$this->template->content->set_global('subtype_options', $f_template->subtype_options());
		$this->template->content->set_global('subtype', null);
		$this->template->content->set_global('value', null);

		$this->template->content->set_global('fs', \Fieldset::forge(), false);
	}

	public function action_tablerow()
	{
		$coltype = \Input::post('type');
		$subtype = \Input::post('subtype');
		$value = \Input::post('value');
		$template = new FieldTemplate($coltype);

		if ($subtype)
		{
			$template->subtype($subtype);
			$value = $template->value();
		}

		$row = \View::forge('form_row');

		$row->i = \Input::post('i');

		$row->type = $coltype;
		$row->type_options = FieldTemplate::type_options();

		$row->subtype_options = $template->subtype_options();
		$row->subtype = $subtype;

		$row->value = $value;

		$row->set('fs', \Fieldset::forge(), false);

		// TODO: extra data

		return \Response::forge($row);
	}
}
