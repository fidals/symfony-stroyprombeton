<?php

namespace App\CatalogBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;

class Order
{
    private $person = '';
    private $phone = '';
    private $email = '';
    private $deliveryAdress = '';
    private $comment = '';

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('person', new NotBlank());
        $metadata->addPropertyConstraint('phone', new NotBlank());
        $metadata->addPropertyConstraint('email', new NotBlank());
        $metadata->addPropertyConstraint('deliveryAdress', new NotBlank());
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setDeliveryAdress($deliveryAdress)
    {
        $this->deliveryAdress = $deliveryAdress;
    }

    public function getDeliveryAdress()
    {
        return $this->deliveryAdress;
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
}