<?php

namespace App\MainBundle\Extension;

class AjaxError extends AjaxResponse
{
	public function __construct($data = array(), $status = 200, $headers = array())
	{
		parent::__construct($data, false, $status, $headers);
	}
}