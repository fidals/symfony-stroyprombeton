<?php

namespace App\CatalogBundle\Controller;

use App\CatalogBundle\Entity\Category;
use App\CatalogBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\CatalogBundle\Extension\Transliteration;

/**
 * Контроллер каталога
 * Его "уникальность" в том, что он по сути частично занимается роутингом (в методах exploreXXXXXX)
 *
 * Class CatalogController
 * @package App\CatalogBundle\Controller
 */
class CatalogController extends Controller
{

    public function productAction($productId)
    {
        $prodRp=$this->getDoctrine()->getRepository('AppCatalogBundle:Product');
        $product=$prodRp->findOneById($productId);
        $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
        $cat = $catRp->findOneById($product->getCategoryId());
        $catArray[]=$cat;
        while($cat->getParent())
        {
            $cat=$cat->getParent();
            $catArray[]=$cat;
        }
        krsort($catArray);
        return $this->render('AppCatalogBundle:Default:product.explore.html.twig', array(
             'product'=> $product,'categories'=>$catArray,
        ));

    }
    public function categoryAction($categoryId)
    {
        $prodRp=$this->getDoctrine()->getRepository('AppCatalogBundle:Product');
        $products=$prodRp->findByCategoryId($categoryId);
        $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
        $category= $cat = $catRp->findOneById($categoryId);
        $catArray[]=$cat;
        while($cat->getParent())
        {
            $cat=$cat->getParent();
            $catArray[]=$cat;
        }
        krsort($catArray);

        return $this->render('AppCatalogBundle:Catalog:category.html.twig', array(
            'products'=> $products,'cat'=>$category,'categories'=>$catArray,
        ));
    }
    public function deviceAction($deviceId)
    {
        $devRp=$this->getDoctrine()->getRepository('AppCatalogBundle:Device');
        $device=$devRp->findOneById($deviceId);
        $prodRp=$this->getDoctrine()->getRepository('AppCatalogBundle:Product');
        $products=$prodRp->findByDeviceId($deviceId);
        return $this->render('AppCatalogBundle:Catalog:category.html.twig', array(
            'products'=> $products,'device'=>$device
        ));
    }
    public function createAction()
    {
        $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
        $categories = $catRp->findOneById(1);
        $category= new Category;
        $category->setName('Пальчиковые');
        $category->setParent($categories);
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();
        return new Response('Created product id '.$category->getId());
    }
    public function searchAction(Request $request)
    {
        $product= new Product();
        $form = $this->createFormBuilder($product)
            ->add('name', 'search', array('label' => ' '))
            ->getForm();
            $form->bind($request);
        return $this->render('AppMainBundle:Default:search.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function searchShowAction(Request $request)
    {
        $product= new Product();
        $form = $this->createFormBuilder($product)
            ->add('name', 'search', array('label' => ' '))
            ->getForm();
        $form->bind($request);
        $find=$product->getName();
        $prodRp=$this->getDoctrine()->getRepository('AppCatalogBundle:Product');
        $products=$prodRp->search('name',$find);
        return $this->render('AppCatalogBundle:Catalog:search.html.twig', array(
            'products'=>$products,
            'form' => $form->createView(),
        ));


    }

}