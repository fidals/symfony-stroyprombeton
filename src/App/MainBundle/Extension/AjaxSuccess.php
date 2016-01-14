<?php

namespace App\MainBundle\Extension;

class AjaxSuccess extends AjaxResponse
{
	public function __construct($data = array(), $status = 200, $headers = array())
	{
		parent::__construct($data, true, $status, $headers);
	}
}