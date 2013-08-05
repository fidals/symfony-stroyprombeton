<?php

namespace App\MainBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\ClosureTreeRepository;

class ServiceRepository extends ClosureTreeRepository
{
    public function findPage(array $aliasArr)
    {
        $res = $this->findByAlias(end($aliasArr));
        if(!empty($res)) {
            foreach($res as $page) {
                $path = $this->getPath($page);
                $pathArr = array();
                foreach($path as $pathUnit) {
                    $pathArr[] = $pathUnit->getAlias();
                }
                if(!array_diff($aliasArr, $pathArr)) {
                    return $page;
                }
            }
            return $res[0];
        }
        return false;
    }

//    public function addPage()
//    {
//        $parent = $this->findOneByAlias('korporativi');
//        $childAliases = array('na-8-marta');
//        //var_dump($parent);
//        foreach($childAliases as $al){
//            $child = $this->findOneByAlias($al);
//            //var_dump($child);
//            $child->setParent($parent);
//        }
//        return 0;
//    }
}