<?php

namespace App\MainBundle\Entity;

use Gedmo\Tree\Entity\MappedSuperclass\AbstractClosure;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="service_closures")
 * @ORM\Entity
 */
class ServiceClosure extends AbstractClosure {}