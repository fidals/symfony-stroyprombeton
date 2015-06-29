<?php
namespace App\MainBundle\Command;

use App\CatalogBundle\Command\SitemapCommand;
use App\CatalogBundle\Entity\Category;
use App\CatalogBundle\Entity\Product;
use App\MainBundle\Entity\Territory;
use App\MainBundle\Entity\Object;
use App\MainBundle\Entity\Post;
use App\MainBundle\Entity\StaticPage;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

	/**
	 * Идентификатор корня новостей
	 */
	const ROOT_POST_ID = 12526;

	/**
	 * Идентификатор корня всех территорий
	 */
	const ROOT_TERRITORY_ID = 12438;

	/**
	 * Массив игнорируемых id при генерации url
	 * @var array
	 */
	public static $ignoreIds = array(12608);


	/**
	 * Массив id статических страниц
	 * @var array
	 */
	public static $staticPages = array(3, 4, 7, 8, 9, 624, 12435);

	public $pdo = null;

	/**
	 * Массив сущностей, которые будут очищены при импорте
	 * @var array
	 */
	public static $truncateEntities = array(
		'AppCatalogBundle:Category',
		'AppCatalogBundle:CategoryClosure',
		'AppCatalogBundle:Product',
		'AppMainBundle:Post',
		'AppMainBundle:StaticPage',
		'AppMainBundle:Territory',
		'AppMainBundle:Object'
	);

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

		$em = $this->getContainer()->get('doctrine')->getManager();

		$connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
		foreach(self::$truncateEntities as $entityName) {
			$truncateSql = $platform->getTruncateTableSQL($em->getClassMetadata($entityName)->getTableName());
			$connection->executeUpdate($truncateSql);
		}
		$connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->migrateCategories();
		$this->migrateProducts();
		$this->migrateTerritories();
		$this->migrateObjects();
		$this->migratePosts();
		$this->migrateStaticPages();
	}

	private function migrateCategories($rootId = self::ROOT_CATEGORY_ID, $parentCategory = null)
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
				$category->setId((int) $row['id']);

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

	private function migratePosts($rootPostId = self::ROOT_POST_ID)
	{
		$em = $this->getContainer()->get('doctrine')->getManager();

		$query = 'SELECT id, pagetitle, introtext, content, createdon FROM '
			. self::MODX_SITE_CONTENT . ' WHERE parent = ' . $rootPostId;

		$posts = $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
		foreach($posts as $p) {

			$postProperties = $this->getContentProperties($p['id']);

			$content = preg_replace_callback(
				'/\[\~\d+\~\]/',
				function($matches) {
					$matchInt = (int) preg_replace('/\D+/', '', $matches[0]);
					return $this->getPathExpression($matchInt);
				},
				$p['content']
			);

			$content = str_replace('"images/', '"/assets/images/', $content);
			$content = str_replace('"assets/', '"/assets/', $content);

			$post = new Post();
			$post->setId($p['id']);
			$post->setName($p['pagetitle']);
			$post->setDescription($p['introtext']);
			$post->setIntroText($p['introtext']);
			$post->setText($content);
			$post->setDate(date_create($postProperties['date']));
			$em->persist($post);
		}
		$em->flush();
	}

	private function migrateStaticPages()
	{
		$em = $this->getContainer()->get('doctrine')->getManager();

		$query = 'SELECT id, pagetitle, longtitle, alias, description, content, createdon FROM '
			. self::MODX_SITE_CONTENT . ' WHERE id IN (' . implode(',', self::$staticPages) . ')';

		$pages = $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
		foreach($pages as $pageRow) {

			$content = preg_replace_callback(
				'/\[\~\d+\~\]/',
				function($matches) {
					$matchInt = (int) preg_replace('/\D+/', '', $matches[0]);
					return $this->getPathExpression($matchInt);
				},
				$pageRow['content']
			);

			$content = str_replace('"images/', '"/assets/images/', $content);
			$content = str_replace('"assets/', '"/assets/', $content);
			$content = preg_replace('/href="[\/]*(?!assets)(?!http)([A-z1-9\/\-]+)\/"/', "href=\"{{path('app_main_staticpage', {'alias': '$1'})}}\"", $content);

			$staticPage = new StaticPage();
			$staticPage->setId($pageRow['id']);
			$staticPage->setTitle($pageRow['longtitle']);
			$staticPage->setName($pageRow['pagetitle']);
			$staticPage->setDescription($pageRow['description']);
			$staticPage->setAlias($pageRow['alias']);
			$staticPage->setText($content);
			$staticPage->setDate(date_create(date("Y-m-d H:i:s", $pageRow['createdon'])));
			$em->persist($staticPage);
		}
		$em->flush();
	}

	// Этот метод расширяет код того что в ветке branch-274
	private function getPathExpression($entityId)
	{
		if(array_search($entityId, self::$staticPages) !== false) {
			$alias = $this->pdo->query('SELECT alias FROM ' . self::MODX_SITE_CONTENT . ' WHERE id = ' . $entityId)->fetchColumn();
			$routeName = 'app_main_staticpage';
			$args = array('alias' => $alias);
		} else {
			$catRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Category');
			$category = $catRp->find($entityId);
			if(!empty($category)) {
				$rootCategoryUrl = SitemapCommand::$baseCats[$catRp->getPath($category)[0]->getId()];
				$routeName = 'app_catalog_explore_category';
				$args = array(
					'catUrl' => $rootCategoryUrl,
					'section' => $entityId
				);
			} else {
				$prodRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Product');
				$product = $prodRp->find($entityId);
				if(!empty($product)) {
					$productSection = $product->getCategory()->getId();
					$productCatUrl = SitemapCommand::$baseCats[$catRp->getPath($product->getCategory())[0]->getId()];
					$routeName = 'app_catalog_explore_category';
					$args = array(
						'catUrl' => $productCatUrl,
						'section' => $productSection,
						'gbi' => $entityId
					);
				}
			}
		}
		if(isset($routeName)) {
			return '{{ path("' . $routeName . '"' . (($args) ? ', ' . json_encode($args) : "") . ') }}';
		} else {
			if(array_search($entityId, self::$ignoreIds) === false) {
				echo 'Не определена сущность для ' . $entityId . "\n";
				return '';
			};
		}
	}

	private function migrateTerritories($rootTerritoryId = self::ROOT_TERRITORY_ID)
	{
		$em = $this->getContainer()->get('doctrine')->getManager();

		$query = 'SELECT id, pagetitle, published, introtext, createdon FROM '
			. self::MODX_SITE_CONTENT . ' WHERE parent = ' . $rootTerritoryId;

		$territories = $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
		foreach($territories as $t) {
			$territory = new Territory();
			$territory->setId($t['id']);
			$territory->setName($t['pagetitle']);
			$territory->setTranslitName($t['introtext']);
			$territory->setIsActive($t['published']);
			$territory->setDate(date_create(date("Y-m-d H:i:s", $t['createdon'])));
			$em->persist($territory);
		}
		$em->flush();
	}

	private function migrateObjects($rootTerritoryId = self::ROOT_TERRITORY_ID)
	{
		$em = $this->getContainer()->get('doctrine')->getManager();

		$query = 'SELECT id, pagetitle, longtitle, parent, description, alias, content, published, createdon FROM '
			. self::MODX_SITE_CONTENT . ' WHERE parent IN (SELECT id FROM '
			. self::MODX_SITE_CONTENT . ' WHERE parent = ' . $rootTerritoryId . ')';

		$objects = $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
		$territoryRp = $this->getContainer()->get('doctrine')->getRepository('AppMainBundle:Territory');
		foreach($objects as $obj) {
			$object = new Object();

			$pregLinks = preg_replace_callback(
				'/\[\~\d+\~\]/',
				function($matches) {
					$matchInt = (int) preg_replace('/\D+/', '', $matches[0]);
					return $this->getPathExpression($matchInt);
				},
				$obj['content']
			);

			// меняем "assets/ на "/assets/
			$pregAll = str_replace('"assets/', '"/assets/', $pregLinks);

			$object->setText($pregAll);

			$object->setId($obj['id']);
			$object->setName($obj['pagetitle']);
			$object->setTitle($obj['longtitle']);
			$object->setDescription($obj['description']);
			$object->setAlias($obj['alias']);
			$object->setIsActive($obj['published']);
			$object->setDate(date_create(date("Y-m-d H:i:s", $obj['createdon'])));
			$object->setTerritory($territoryRp->find($obj['parent']));
			$em->persist($object);
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
		return array_merge($properties, $this->getContentProperties($categoryId));
	}

	private function getContentProperties($contentId)
	{
		$query = 'SELECT name, value FROM ' . self::MODX_SITE_TMPLVAR_CONTENTVALUES . ' as a '
			. 'LEFT JOIN ' . self::MODX_SITE_TMPLVARS . ' as b ON a.tmplvarid = b.id '
			. 'WHERE a.contentid = ' . $contentId;
		$result = $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);

		$properties = array();
		if($result) {
			foreach($result as $property) {
				$properties[$property['name']] = $property['value'];
			}
		}
		return $properties;
	}
}