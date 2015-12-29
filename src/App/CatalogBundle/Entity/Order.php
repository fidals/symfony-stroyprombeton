<?php

namespace App\CatalogBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Assert\File;

class Order
{
	private $person  = '';
	private $phone   = '';
	private $email   = '';
	private $company = '';
	private $deliveryAddress = '';
	private $comment = '';
	private $file;

	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		$metadata->addPropertyConstraint('person',  new NotBlank());
		$metadata->addPropertyConstraint('phone',   new NotBlank());
		$metadata->addPropertyConstraint('phone',   new Regex(array('pattern' => "/^((8|0|\+\d{1,2})[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i")));
		$metadata->addPropertyConstraint('email',   new NotBlank());
		$metadata->addPropertyConstraint('company', new NotBlank());
		$metadata->addPropertyConstraint('files',   new File());
	}

	public function setFile($file)
	{
		$this->file = $file;
	}

	public function getFile()
	{
		return $this->file;
	}

	public function setComment($comment)
	{
		$this->comment = $comment;
	}

	public function getComment()
	{
		return $this->comment;
	}

	public function setDeliveryAddress($deliveryAddress)
	{
		$this->deliveryAddress = $deliveryAddress;
	}

	public function getDeliveryAddress()
	{
		return $this->deliveryAddress;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setCompany($company)
	{
		$this->company = $company;
	}

	public function getCompany()
	{
		return $this->company;
	}

	public function setPerson($person)
	{
		$this->person = $person;
	}

	public function getPerson()
	{
		return $this->person;
	}

	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	public function getPhone()
	{
		return $this->phone;
	}
}