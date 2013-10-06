<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Category;
use App\CatalogBundle\Entity\CategoryClosure;
use App\CatalogBundle\Entity\Gbi;
use App\CatalogBundle\Entity\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('app_main_index'), 301);
    }
}