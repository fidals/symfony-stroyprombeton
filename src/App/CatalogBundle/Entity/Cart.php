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
	 * @param $id
	 * @param $count
	 */
	public function addProduct($id, $count)
	{
		$this->products[$id] = (isset($this->products[$id])) ? $this->products[$id] + $count : $count;
	}

    /**
     * Метод для прямой установки количества товара в заказе.
     * Полезен для апдейта корзины при изменениях в дропдауне.
     *
     * @param int $id нужного нам товара
     * @param int $newCount его новое количество в заказе.
     */
    public function setCount($id, $newCount)
    {
        $this->products[$id] = $newCount;
    }

	public function removeProduct($id, $count)
	{
		if(isset($this->products[$id])) {
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

	/**
	 * Возвращает кол-во товаров в корзине.
	 * Именно кол-во товаров, а не позиций
	 * @return int
	 */
	public function getTotalProductsCount()
	{
		return (int) array_sum($this->getProducts());
	}
}