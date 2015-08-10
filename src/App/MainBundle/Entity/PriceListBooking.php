<?php

namespace App\MainBundle\Entity;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;

class PriceListBooking
{
	private $person = '';
	private $phone = '';
	private $email = '';
	private $company = '';
	private $city = '';
	private $activity = '';
	private $site = '';

	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		$metadata->addPropertyConstraint('phone', new NotBlank());
		$metadata->addPropertyConstraint('phone', new Regex(array('pattern' => "/^((8|0|\+\d{1,2})[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i")));
		$metadata->addPropertyConstraint('email', new NotBlank());
		$metadata->addPropertyConstraint('company', new NotBlank());
		$metadata->addPropertyConstraint('city', new NotBlank());
		$metadata->addPropertyConstraint('activity', new NotBlank());
	}

	public function setActivity($activity)
	{
		$this->activity = $activity;
	}

	public function getActivity()
	{
		return $this->activity;
	}

	public function setCity($city)
	{
		$this->city = $city;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function setCompany($company)
	{
		$this->company = $company;
	}

	public function getCompany()
	{
		return $this->company;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
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

	public function setSite($site)
	{
		$this->site = $site;
	}

	public function getSite()
	{
		return $this->site;
	}
}