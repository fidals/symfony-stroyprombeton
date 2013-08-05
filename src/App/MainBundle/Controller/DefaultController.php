<?php

namespace App\MainBundle\Controller;

use App\MainBundle\Entity\Service;
use App\MainBundle\Entity\StaticPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;

class DefaultController extends Controller
{

    /*
     * UPDATE  `static_pages` dest,
        (
        SELECT a.title, a.alias
        FROM  `static_pages_r` a,  `static_pages` b
        WHERE a.title = b.title
        AND a.alias != b.alias
        )src
        SET dest.alias = src.alias WHERE dest.title = src.title
     */
    public function migrate1Action()
    {
        $greenArr = explode(" ", "26 38 32 35 37 39 40 41");
        $pagesStokeRp = $this->getDoctrine()->getRepository('AppMainBundle:PagesStoke');
        $serviceRp = $this->getDoctrine()->getRepository('AppMainBundle:Service');
        $staticPageRp = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');
        $allPages = $pagesStokeRp->findAll();
        $em = $this->getDoctrine()->getEntityManager();
        foreach($allPages as $stoke) {
            $service = $serviceRp->find($stoke->getId());
            $service->setParent($serviceRp->find($stoke->getParent()));
            /*if(!($service instanceof Service)) {
                $service = new Service();
                $service->setId($stoke->getId());
                $service->setTitle($stoke->getPagetitle());
                $service->setMenutitle($stoke->getMenutitle());
                $service->setTemplate($stoke->getTemplate());
                $service->setContent($stoke->getContent());
                $service->setDescription($stoke->getDescription());
                $service->setAlias($stoke->getAlias());
                $service->setIntrotext($stoke->getIntrotext());
                $service->setPublished($stoke->getPublished());
                //$service->setParent($serviceRp->find($stoke->getParent()));
                $em->persist($service);
            }*/
        }
        $em->flush();
    }

    public function migrate2Action()
    {
        $greenArr = explode(" ", "26 38 32 35 37 39 40 41");
        $pagesStokeRp = $this->getDoctrine()->getRepository('AppMainBundle:PagesStoke');
        $serviceRp = $this->getDoctrine()->getRepository('AppMainBundle:Service');
        $staticPageRp = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');
        $allPages = $pagesStokeRp->findAll();
        $em = $this->getDoctrine()->getEntityManager();
        foreach($allPages as $page) {
            $service = $serviceRp->find($page->getId());
            $path = $serviceRp->getPath($service);

            if(array_search($path[0]->getId(), $greenArr) === false) {
                $em->remove($service);
                /*$alias = array();
                foreach($path as $pathNode) {
                    $alias[] = $pathNode->getAlias();
                }
                $alias = implode('/', $alias);
                $staticPage = new StaticPage();
                $staticPage->setId($service->getId());
                $staticPage->setTitle($service->getTitle());
                $staticPage->setMenutitle($service->getMenutitle());
                $staticPage->setTemplate($service->getTemplate());
                $staticPage->setContent($service->getContent());
                $staticPage->setDescription($service->getDescription());
                $staticPage->setAlias($alias);
                $staticPage->setIntrotext($service->getIntrotext());
                $staticPage->setPublished($service->getPublished());
                $em->persist($staticPage);*/
            }
            /*
            $service = new Service();
            $service->setId($stoke->getId());
            $service->setTitle($stoke->getPagetitle());
            $service->setMenutitle($stoke->getMenutitle());
            $service->setTemplate($stoke->getTemplate());
            $service->setContent($stoke->getContent());
            $service->setDescription($stoke->getDescription());
            $service->setAlias($stoke->getAlias());
            $service->setIntrotext($stoke->getIntrotext());
            $service->setPublished($stoke->getPublished());
            $em->persist($service);*/
        }
        $em->flush();
        die('success too');
    }

    public function migrate3Action()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $staticPageRp = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');
        $allStatic = $staticPageRp->findAll();
        foreach($allStatic as $staticPage) {
            $alias = $staticPage->getAlias();
            $staticPage->setAlias(str_replace('price/', '', $alias));
        }
        $em->flush();
        die('success');
    }

    public function migrate4Action()
    {
        $serviceRp = $this->getDoctrine()->getRepository('AppMainBundle:Service');
        $staticPageRp = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');

        $em = $this->getDoctrine()->getEntityManager();
        $tmplRp = $this->getDoctrine()->getRepository('AppMainBundle:Tmplvar');
        $allTmpl = $tmplRp->findAll();
        foreach($allTmpl as $tmpl) {
            $service = $serviceRp->find($tmpl->getContentid());
            if(!empty($service)) {
                $content = $service->getContent();
                strtr($content, array(
                    '[!header-build!]' => '',
                    '[!childs-docs!]'  => '',
                    '[!photos-sections!]' => '',
                    '[!detskij-prazdnik!]' => '',
                    '[!team-building!]'    => '',
                ));
                //$content .= '<br>' . $tmpl->getValue();
                $service->setContent($content);
            } else {
                $staticPage = $staticPageRp->find($tmpl->getContentid());
                if(!empty($staticPage)) {
                    $content = $staticPage->getContent();
                    strtr($content, array(
                        '[!header-build!]' => '',
                        '[!childs-docs!]'  => '',
                        '[!photos-sections!]' => '',
                        '[!detskij-prazdnik!]' => '',
                        '[!team-building!]'    => '',
                    ));
                    //$content .= '<br>' . $tmpl->getValue();
                    $staticPage->setContent($content);
                } else {
                    die('!not success');
                }
            }
            $em->flush();
        }
        die('success');
    }

    public function migrate5Action()
    {
        $serviceRp = $this->getDoctrine()->getRepository('AppMainBundle:Service');
        $staticPageRp = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');

        $em = $this->getDoctrine()->getEntityManager();

        $services = $serviceRp->findAll();
        $staticPages = $staticPageRp->findAll();
        foreach($services as $service) {
            $service->setContent(strtr($service->getContent(), array(
                '[!header-build!]' => '',
                '[!childs-docs!]'  => '',
                '[!photos-sections!]' => '',
                '[!detskij-prazdnik!]' => '',
                '[!team-building!]'    => '',
            ))
           );
           $em->flush();
        }

        foreach($staticPages as $sp) {
            $sp->setContent(strtr($sp->getContent(), array(
                '[!header-build!]' => '',
                '[!childs-docs!]'  => '',
                '[!photos-sections!]' => '',
                '[!detskij-prazdnik!]' => '',
                '[!team-building!]'    => '',
            )));
            $em->flush();
        }
        die('success');
    }

    public function indexAction()
    {
		return $this->render('AppMainBundle:StaticPage:mainPage.html.twig');
    }

	public function dbPageAction($alias)
    {
        $alias = trim($alias, '/');
        $aliasArr = explode('/', $alias);

		$staticPageRp = $this->getDoctrine()->getRepository('AppMainBundle:StaticPage');
        $serviceRp = $this->getDoctrine()->getRepository('AppMainBundle:Service');
        $page = $staticPageRp->findOneByAlias($alias);
        if(empty($page)) {
            $page = $serviceRp->findPage($aliasArr);
            if(!empty($page)) {
                $page->childrens = $serviceRp->children($page, true);
                $page->path = $serviceRp->getPath($page);
            } else {
                // 404
                $page = $staticPageRp->find(2);
            }
        }

        $rootNodes = $serviceRp->getRootNodes();
        foreach($rootNodes as $rootNode) {
            $treeNode = $serviceRp->buildTreeArray($serviceRp->getNodesHierarchy($rootNode, true, array(), true));
            if(!empty($page->path[0]) && ($treeNode[0]['id'] == $page->path[0]->getId())) {
                $treeNode[0]['activeLink'] = true;
            }
            $tree[] = $treeNode[0];
        }
        return $this->render('AppMainBundle:StaticPage:dbPage.html.twig',
            array(
                'page' => $page,
                'tree' => $tree
            )
        );
    }

//    public function migrateAddServiceAction()
//    {
//        $serviceRp = $this->getDoctrine()->getRepository('AppMainBundle:Service');
//        $serviceRp->addPage();
//        return new Response('success!');
//    }

}
