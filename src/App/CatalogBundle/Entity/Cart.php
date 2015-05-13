<?php

namespace App\CatalogBundle\Entity;

class Cart
{
    /**
     * array_key - id продукта в базе
     * array_value - кол-во продуктов
     * @var array
     */
    private $products = array();

    /**
     * Всего продуктов в козине
     * @var int
     */
    private $total_products_count = 0;

    /**
     * @param $id
     * @param $count
     */
    public function addProduct($id, $count) {
        $this->products[$id] = (isset($this->products[$id])) ? $this->products[$id] + $count : $count;
        $this->total_products_count += $count;
        // TODO - зачем здесь такое интересное echo ? Протести и снеси, если тест ок
        echo '';
    }

    public function setProducts($products) {
        $this->products = $products;
    }

    public function getProducts() {
        return $this->products;
    }

    public function setTotalProductsCount($total_products_count) {
        $this->total_products_count = $total_products_count;
    }

    public function getTotalProductsCount() {
        return $this->total_products_count;
    }

    public function serialize() {
        if(empty($this->products)) { return false; }

        $cart_prods_arr = array();
        foreach($this->products as $id => $count) {
            $cart_prods_arr[] = $id . ':' . $count;
        }
        return implode('-', $cart_prods_arr);
    }
}
