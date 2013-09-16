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

class CatalogController extends Controller
{
    // ugly urls
    public $baseCats = array(
        537 => 'prom-stroy',
        538 => 'dor-stroy',
        539 => 'ingener-stroy',
        540 => 'energy-stroy',
        541 => 'blag-territory',
        542 => 'neftegaz-stroy'
    );

    // "custom router"
    public function exploreRouteAction($catUrl)
    {
        $sectionId = $this->getRequest()->query->get('section');
        $gbiId = $this->getRequest()->query->get('gbi');

        if(!empty($catUrl) && !empty($sectionId) && !empty($gbiId)) {
            return $this->forward('AppCatalogBundle:Catalog:exploreProduct', array(
                'catUrl'    => $catUrl,
                'sectionId' => (int) $sectionId,
                'gbiId'     => (int) $gbiId
            ));
        } elseif(!empty($catUrl) || !empty($sectionId)) {
            return $this->forward('AppCatalogBundle:Catalog:exploreCategory', array(
                'catUrl'    => $catUrl,
                'sectionId' => (int) $sectionId,
            ));
        }
        die('Page is not found');
    }

    public function exploreCategoryAction($catUrl, $sectionId)
    {
        // search category ID

        $catId = array_search($catUrl, $this->baseCats);
        $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
        $category = $catRp->find(!empty($sectionId) ? $sectionId : $catId);
        $parents = $catRp->getPath($category);
        if($this->isParent($category,$catUrl)){
            $hierarchyOptions = array(
                'childSort' => array(
                    'field' => 'title',
                    'dir'   => 'asc'
                )
            );

            $childs = $catRp->buildTreeArray($catRp->getNodesHierarchy($category, false, $hierarchyOptions));

            if(!empty($childs)) {
                return $this->render('AppCatalogBundle:Catalog:category.explore.html.twig', array(
                    'parents'   => $parents,
                    'childs'    => $childs,
                    'catUrl'    => $this->baseCats[$catId],
                    'category'  => $category
                ));
            } else {
                $prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
                $products = $prodRp->findBy(
                    array('sectionId' => $sectionId),
                    array('nomen' => 'ASC')
                );
                return $this->render('AppCatalogBundle:Catalog:section.explore.html.twig', array(
                    'parents'  => $parents,
                    'catUrl'   => $this->baseCats[$catId],
                    'section'  => $category,
                    'products' => $products
                ));
            }
        }
        else{
          //TODO:сделать редиректы
            return $this->render("AppMainBundle:StaticPage:404.html.twig");
        }
    }

    public function exploreProductAction($catUrl, $sectionId, $gbiId)
    {
        // search category ID
        $catId = array_search($catUrl, $this->baseCats);

        $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
        $prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
        $section = $catRp->find($sectionId);
        $parents = $catRp->getPath($section);
        $product = $prodRp->find($gbiId);
        $alsoPurchase = $prodRp->findBySectionId($product->getSectionId());

        return $this->render('AppCatalogBundle:Catalog:product.explore.html.twig', array(
            'parents'      => $parents,
            'catUrl'       => $this->baseCats[$catId],
            'section'      => $section,
            'product'      => $product,
            'alsoPurchase' => $alsoPurchase
        ));
    }

    public function searchAction()
    {
        $condition = $this->getRequest()->query->get('condition');
        $page = $this->getRequest()->query->get('page', 1);

        if(!empty($condition)) {
            $prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
            $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');

            $products = $prodRp->search($condition, $page);
            foreach($products as &$product) {
                $category = $catRp->find($product->getSectionId());
                $path = $catRp->getPath($category);
                $product->catUrl = $this->baseCats[$path[0]->getId()];
                $product->section = $category;
                $product->path = $path;
            }

            return $this->render('AppCatalogBundle:Catalog:search.html.twig', array(
                'products' => $products
            ));
        }
        return new Response();
    }

    public function suggestAction()
    {
        $term = $this->getRequest()->query->get('term');

        if(!empty($term)) {
            $prodRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Product');
            $jsonSrv = new JsonEncoder();
            $result = $prodRp->searchAutocomplete($term);

            $json = $jsonSrv->encode($result, JsonEncoder::FORMAT);
            return new Response($json);
        }
        return new Response();
    }

    private  function isParent($category,$catUrl){

        $catRp = $this->getDoctrine()->getRepository("AppCatalogBundle:Category");
        $parents = $catRp->getPath($category);
        foreach($parents as $parent){
            if($parent->getAlias() == $catUrl)
                return true;
        }
        return false;
    }


/*
    public function migrate1Action()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $catArr = array(
            537 => array('prom-stroy', 'Общегражданское и промышленное строительство'),
            538 => array('dor-stroy', 'Дорожное строительство'),
            539 => array('ingener-stroy', 'Строительство инженерных сетей'),
            540 => array('energy-stroy', 'Строительство энергетических объектов'),
            541 => array('blag-territory', 'Благоустройство территорий'),
            542 => array('neftegaz-stroy', 'Нефтегазовое строительство')
        );

        foreach($catArr as $id => $data) {
            $cat = new Category();
            $cat->setId($id)
                ->setName($data[1])
                ->setTitle($data[1])
                ->setAlias($data[0])
                ->setCoefficient(1)
                ->setIsActive(1);
            $cat->setOrder(0);

            $em->persist($cat);
            $em->flush();
            $em->clear();
        }

        die('success 1');
    }

    public function migrate2Action()
    {
        $baseCats = array(
            537 => array('title' => 'Общегражданское и промышленное строительство', 'uri' => 'prom-stroy'),
            538 => array('title' => 'Дорожное строительство','uri' =>  'dor-stroy'),
            539 => array('title' => 'Строительство инженерных сетей','uri' => 'ingener-stroy'),
            540 => array('title' => 'Строительство энергетических объектов','uri' => 'energy-stroy'),
            541 => array('title' => 'Благоустройство территорий', 'uri' => 'blag-territory'),
            542 => array('title' => 'Нефтегазовое строительство', 'uri' => 'neftegaz-stroy')
        );

        $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
        $gbiRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Gbi');
        $em = $this->getDoctrine()->getEntityManager();
        $gbis= $gbiRp->findAllOrderedBy('parentId', 'ASC');

        foreach($gbis as $gbi) {
            $cat = new Category();
            $cat->setId($gbi->getId())
                ->setName($gbi->getNameContent())
                ->setTitle($gbi->getName())
                ->setAlias($gbi->getAlias())
                ->setMark($gbi->getWorkDocs())
                ->setDescription($gbi->getDesc())
                ->setCoefficient($gbi->getKoefPrice())
                ->setIsActive(1);

            $gbiParentId = $gbi->getParentId();
            $gbiOrder = $gbi->getOrder();

            if(!empty($gbiParentId)) {
                $cat->setParent($catRp->find($gbi->getParentId()));
            } else {
                $gbiUriParent = $gbi->getUriParent();
                foreach($baseCats as $id => $baseCat) {
                    if($baseCat['uri'] == $gbiUriParent) {
                        $cat->setParent($catRp->find($id));
                    }
                }
            }

            if(!empty($gbiOrder)) {
                $cat->setOrder($gbi->getOrder());
            } else {
                $cat->setOrder(0);
            }

            $em->persist($cat);
            $em->flush();
            $em->clear();
        }
        die('success');
    }

    public function migrate3Action()
    {
        $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
        $gbiRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Gbi');
        $em = $this->getDoctrine()->getEntityManager();
        $gbis= $gbiRp->findAllOrderedBy('parentId', 'ASC');

        foreach($gbis as $gbi) {
            $cat = $catRp->find($gbi->getId());
            $gbiParentId = $gbi->getParentId();

            if(!empty($gbiParentId)) {
                $cat->setParent($catRp->find($gbiParentId));
            }

            $em->persist($cat);
            $em->flush();
            $em->clear();
        }
        die('success too');
    }

    /*
    public function exploreSectionAction($catUrl, $sectionId)
    {
        // search category ID
        $catId = array_search($catUrl, $this->baseCats);

        $catRp = $this->getDoctrine()->getRepository('AppCatalogBundle:Category');
        $section = $catRp->find($sectionId);
        $parents = $catRp->getPath($section);

        $hierarchyOptions = array(
            'childSort' => array(
                'field' => 'title',
                'dir'   => 'asc'
            )
        );

        $childs = $catRp->buildTreeArray($catRp->getNodesHierarchy($section, false, $hierarchyOptions));

        if(!empty($childs)) {
            return $this->render('AppCatalogBundle:Default:category.explore.html.twig', array(
                'parents'   => $parents,
                'childs'    => $childs,
                'catUrl'    => $this->baseCats[$catId],
                'category'  => $section
            ));
        } else {
            $products = $
            return $this->render('AppCatalogBundle:Default:section.explore.html.twig', array(
                'parents'  => $parents,
                'childs'   => $childs,
                'catUrl'   => $this->baseCats[$catId],
                'section'  => $section,
                'products' => $products
            ));
        }
    }*/
}