<?php

namespace App\CatalogBundle\Entity;

class Cart
{
    private $products = array();
    private $totalProductsCount = 0;

    public function addProduct($productId, $count)
    {
        $this->products[$productId] = (isset($this->products[$productId])) ? $this->products[$productId] + $count : $count;
        $this->totalProductsCount += $count;
        echo '';
    }

    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setTotalProductsCount($totalProductsCount)
    {
        $this->totalProductsCount = $totalProductsCount;
        return $this;
    }

    public function getTotalProductsCount()
    {
        return $this->totalProductsCount;
    }

    public function serialize()
    {
        if(!empty($this->products)) {
            foreach($this->products as $productId => $count) {
                $prodStrArr[] = $productId . ':' . $count;
            }
            return implode('-', $prodStrArr);
        }
        return false;
    }
}
