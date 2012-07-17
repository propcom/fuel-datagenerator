<?php

namespace Datagenerator;


class Controller_Home extends \Controller_Template
{
	public $template = 'template';

	public function action_index()
	{
		$this->template->content = \View::forge('form');
	}
}
