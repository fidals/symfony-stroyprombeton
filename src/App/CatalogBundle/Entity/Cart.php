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
	private $totalProductsCount = 0;

	/**
	 * @param $id
	 * @param $count
	 */
	public function addProduct($id, $count)
	{
		$this->products[$id] = (isset($this->products[$id])) ? $this->products[$id] + $count : $count;
		$this->totalProductsCount += $count;
	}

	public function removeProduct($id, $count)
	{
		if(isset($this->products[$id])) {
			$this->totalProductsCount -= $this->products[$id];
			if($count >= $this->products[$id]) {
				unset($this->products[$id]);
			} else {
				$this->products[$id] -= $count;
			}
		}
	}

	public function setProducts($products)
	{
		$this->products = $products;
	}

	public function getProducts()
	{
		return $this->products;
	}

	public function setTotalProductsCount($total_products_count)
	{
		$this->totalProductsCount = $total_products_count;
	}

	public function getTotalProductsCount()
	{
		return $this->totalProductsCount;
	}
}
