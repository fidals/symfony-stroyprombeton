<?php
namespace App\MainBundle\Command;

use App\CatalogBundle\Entity\Category;
use App\CatalogBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Команда для переноса БД из modx в symfony2
 * Class MigrateCommand
 * @package App\MainBundle\Command
 */
class MigrateCommand extends ContainerAwareCommand
{
	/**
	 * Имя базы данных на modx
	 */
	const MODX_DB = 'shopelecru_pb';

	/**
	 * Названия таблиц modx
	 */
	const MODX_SITE_CONTENT = 'modx_site_content';
	const MODX_SITE_TMPLVARS = 'modx_site_tmplvars';
	const MODX_SITE_TMPLVAR_CONTENTVALUES = 'modx_site_tmplvar_contentvalues';

	/**
	 * Идентификатор корня дерева категорий
	 */
	const ROOT_CATEGORY_ID = 10;

	public $pdo = null;

	protected function configure()
	{
		$this->setName('db:migrate')
			->setDescription('Migrate DB from modx to symfony2');
	}

	protected function initialize()
	{
		// connect to mysql
		$dbHost = $this->getContainer()->getParameter('database_host');
		$dsn = 'mysql:dbname=' . self::MODX_DB . ';host=' . $dbHost;

		$this->pdo = new \PDO(
			$dsn,
			$this->getContainer()->getParameter('database_user'),
			$this->getContainer()->getParameter('database_password')
		);
		$this->pdo->exec('SET NAMES utf8');

		// clean db tables
		$connection = $this->getContainer()->get('doctrine')->getManager()->getConnection();
		$platform   = $connection->getDatabasePlatform();

		$connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
		$truncateSql = $platform->getTruncateTableSQL('categories');
		$connection->executeUpdate($truncateSql);
		$truncateSql = $platform->getTruncateTableSQL('category_closures');
		$connection->executeUpdate($truncateSql);
		$truncateSql = $platform->getTruncateTableSQL('products');
		$connection->executeUpdate($truncateSql);
		$connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->migrateCategories(self::ROOT_CATEGORY_ID);
		$this->migrateProducts();
	}

	private function migrateCategories($rootId, $parentCategory = null)
	{
		$children = $this->findChildren($rootId);
		if($children) {
			$em = $this->getContainer()->get('doctrine')->getManager();

			foreach($children as $row) {
				$properties = $this->getCategoryProperties($row['id']);

				$category = new Category();
				$category->setAlias($row['alias']);
				$category->setCoefficient($properties['coefficient']);
				$category->setName($row['pagetitle']);
				$category->setTitle($row['longtitle']);
				$category->setH1($row['pagetitle']);
				$category->setDescription($row['description']);
				$category->setLinkToStkMetal($properties['stk-metal1']);
				$category->setIsActive($row['published']);
				$category->setText($row['content']);
				$category->setDate(date_create(date("Y-m-d H:i:s", $row['createdon'])));

				if(isset($properties['xml_id'])) {
					$category->setId((int) $properties['xml_id']);
				} else {
					$category->setId((int) $row['id']);
				}

				if($parentCategory) {
					$category->setParent($parentCategory);
				}

				$em->persist($category);
				$em->flush();

				$this->migrateCategories($row['id'], $category);
			}
		}
	}

	private function migrateProducts()
	{
		$catRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Category');
		$em = $this->getContainer()->get('doctrine')->getManager();
		$prodQuery = 'SELECT * FROM ' . self::MODX_SITE_CONTENT . ' as a WHERE a.isfolder = 0 AND a.template = 7';
		$prods = $this->pdo->query($prodQuery)->fetchAll(\PDO::FETCH_ASSOC);
		$prodsCount = count($prods);

		$prodPropsQuery = 'SELECT * FROM ' . self::MODX_SITE_TMPLVAR_CONTENTVALUES . ' AS a
			LEFT JOIN ' . self::MODX_SITE_TMPLVARS . ' AS b ON a.tmplvarid = b.id
			WHERE a.contentid
			IN (
				SELECT id
				FROM ' . self::MODX_SITE_CONTENT . ' AS c
				WHERE c.isfolder = 0
				AND c.template = 7
			)';

		$prodProps = $this->pdo->query($prodPropsQuery)->fetchAll(\PDO::FETCH_ASSOC);
		foreach($prodProps as $productProperty) {
			$prodPropsSrt[$productProperty['contentid']][$productProperty['name']] = $productProperty['value'];
		}

		// делаем массив contentid => xml_id (id на modx и id у нас)
		$catIdMapQuery = 'SELECT contentid, value FROM ' . self::MODX_SITE_TMPLVAR_CONTENTVALUES . ' WHERE tmplvarid = 4';

		$catIdMapArr = $this->pdo->query($catIdMapQuery)->fetchAll(\PDO::FETCH_ASSOC);
		foreach($catIdMapArr as $rel) {
			$catIdMap[$rel['contentid']] = (int) $rel['value'];
		}

		$done = 0;
		foreach($prods as $productData) {
			$product = new Product();
			$productProperties = $prodPropsSrt[$productData['id']];

			// получаем id категории
			if(isset($catIdMap[$productData['parent']])) {
				$parentCatId = $catIdMap[$productData['parent']];
			} else {
				$parentCatId = $productData['parent'];
			}

			$parentCat = $catRp->find($parentCatId);

			$product->setCategory($parentCat);
			$product->setId($productData['id']);
			$product->setTitle($productData['longtitle']);
			$product->setH1($productData['pagetitle']);
			$product->setDescription($productData['description']);
			$product->setText($productData['content']);
			$product->setIsActive($productData['published']);
			$product->setDate(date_create(date("Y-m-d H:i:s", $productData['createdon'])));
			$product->setIntrotext($productData['introtext']);

			if (isset($productProperties['width'])) $product->setWidth($productProperties['width']);
			if (isset($productProperties['height'])) $product->setHeight($productProperties['height']);
			if (isset($productProperties['nomen'])) $product->setNomen($productProperties['nomen']);
			if (isset($productProperties['mark'])) $product->setMark($productProperties['mark']);
			if (isset($productProperties['length'])) $product->setLength($productProperties['length']);
			if (isset($productProperties['weight'])) $product->setWeight($productProperties['weight']);
			if (isset($productProperties['new_price'])) $product->setIsNewPrice($productProperties['new_price']);
			if (isset($productProperties['price'])) $product->setPrice(ceil($productProperties['price'] * 1.1));
			if (isset($productProperties['diameter_out'])) $product->setDiameterOut($productProperties['diameter_out']);
			if (isset($productProperties['diameter_in'])) $product->setDiameterIn($productProperties['diameter_in']);
			if (isset($productProperties['comments'])) $product->setComments($productProperties['comments']);
			if (isset($productProperties['volume'])) $product->setVolume($productProperties['volume']);

			if(isset($productData['name'])) {
				$product->setName($productData['name']);
			} else {
				$product->setName($productData['pagetitle']);
			}

			$em->persist($product);
			$done++;
			if($done % 500 === 0) {
				$em->flush();
				echo (int) ($done / $prodsCount * 100.0) . "%\r";
			}
		}
		$em->flush();
	}

	private function findChildren($categoryId)
	{
		$query = 'SELECT * FROM ' . self::MODX_SITE_CONTENT . ' as a WHERE a.isfolder = 1 AND a.parent = ' . $categoryId;
		return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}

	private function getCategoryProperties($categoryId)
	{
		$properties = array(
			'coefficient' => 1.1,
			'stk-metal1'  => ''
		);

		$query = 'SELECT name, value FROM ' . self::MODX_SITE_TMPLVAR_CONTENTVALUES . ' as a '
			. 'LEFT JOIN ' . self::MODX_SITE_TMPLVARS . ' as b ON a.tmplvarid = b.id '
			. 'WHERE a.contentid = ' . $categoryId;
		$result = $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);

		if($result) {
			foreach($result as $property) {
				$properties[$property['name']] = $property['value'];
			}
		}
		return $properties;
	}
}