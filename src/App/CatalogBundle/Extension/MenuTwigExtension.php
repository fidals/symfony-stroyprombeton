<?php

namespace App\CatalogBundle\Extension;


/**
 * Расширение twig для генерации дерева категорий и для транслита
 *
 * Class MenuTwigExtension
 * @package App\CatalogBundle\Extension
 */
class MenuTwigExtension extends \Twig_Extension
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }


    /**
     * Возвращает фунцкции для twig
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'categoriesTree' => new \Twig_Function_Method($this, 'getCategoriesTree'),
            'translit'       => new \Twig_Function_Method($this, 'translit')
        );
    }

    /**
     * Возврашает дерево категорий
     *
     * @return array
     */
    public function getCategoriesTree()
    {
        $catRp = $this->em->getRepository('AppCatalogBundle:Category');

        $hierarchyOptions = array(
            'childSort' => array(
                'field' => 'name',
                'dir'   => 'asc'
            )
        );

        $rootNodes = $catRp->getRootNodes();
        foreach($rootNodes as $rootNode) {
            $treeNode = $catRp->buildTreeArray($catRp->getNodesHierarchy($rootNode, false, $hierarchyOptions, true));
            $tree[] = $treeNode[0];
        }
        return $tree;
    }

    /**
     * Возвращает транслитерированный текст (по умолчанию cyr -> lat)
     *
     * @param $text входной текст
     * @return mixed
     */
    public function translit($text)
    {
        return Transliteration::get($text);
    }

    public function getName()
    {
        return 'menu_twig_extension';
    }
}