<?php

namespace App\CatalogBundle\Extension;
use Symfony\Component\HttpFoundation\JsonResponse;

;

class AjaxResponse extends JsonResponse
{
	public function __construct($data = array(), $success = true, $status = 200, $headers = array())
	{
		parent::__construct(array_merge($data, array('success' => $success)), $status, $headers);
	}
}