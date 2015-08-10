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
use Symfony\Component\Console\Input\InputOption;
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

	const CATEGORY_IMG_THUMB = '/sections/logo-prozr.png';
	const PRODUCT_IMG_THUMB = '/gbi-photos/prod-alt-image.png';

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
	public static $ignoreIds = array();

	/**
	 * Массив id статических страниц
	 * @var array
	 */
	public static $staticPages = array(3, 4, 7, 8, 9, 624, 12435);


	/**
	 * Массив специальных страниц в виде modx_id => symfony_route
	 * @var array
	 */
	public static $pages = array(
		12608 => 'app_main_price_list_booking'
	);

	/**
	 * Путь до директории с картинками
	 * @var
	 */
	private $imgPath;

	/**
	 * Путь до папки с картинками от modx
	 * @var null
	 */
	private $modxImgPath = null;

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
			->setDescription('Migrate DB from modx to symfony2')
			->addOption(
				'modx-img-path',
				null,
				InputOption::VALUE_REQUIRED,
				'ModX pictures path',
				null
			);
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

		$this->imgPath = __DIR__ . '/../../../../web/assets/images';
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$modxImgPath = $input->getOption('modx-img-path');
		if(!is_null($modxImgPath)) {
			if(file_exists($modxImgPath)) {
				$this->modxImgPath = rtrim($modxImgPath, "/");;
			} else {
				throw new \Exception("Invalid modx_img_path option.\nDirectory " . $modxImgPath . " does not exist.");
			}
		}
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

				if(!empty($this->modxImgPath)) {
					if(!file_exists($this->imgPath . '/sections')) {
						mkdir($this->imgPath . '/sections');
					}
					$catImg = isset($properties['cat_image_tn']) ? $properties['cat_image_tn'] : self::CATEGORY_IMG_THUMB;
					$catImg = str_replace('/assets/images', '', $catImg);
					$catImg = str_replace('assets/images', '', $catImg);
					if($catImg != self::CATEGORY_IMG_THUMB) {
						if(file_exists($this->modxImgPath . $catImg)) {
							$pathInfo = pathinfo($this->modxImgPath . $catImg);
							copy($this->modxImgPath . $catImg, $this->imgPath . '/sections/' . $row['id'] . '.' . $pathInfo['extension']);
						} else {
							echo 'Image ' . $this->modxImgPath . $catImg . ' for category ' . $row['id'] . 'does not exist' . "\n";
						}
					} else {
						if(!file_exists($this->imgPath . self::CATEGORY_IMG_THUMB)) {
							copy($this->modxImgPath . self::CATEGORY_IMG_THUMB, $this->imgPath . self::CATEGORY_IMG_THUMB);
						}
					}
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

		if($this->modxImgPath) {
			// папка для фоток
			if(!file_exists($this->imgPath . '/gbi-photos')) {
				mkdir($this->imgPath . '/gbi-photos');
			}
			copy($this->modxImgPath . self::PRODUCT_IMG_THUMB, $this->imgPath . self::PRODUCT_IMG_THUMB);
		}

		$done = 0;
		foreach($prods as $productData) {
			$product = new Product();
			$productProperties = $prodPropsSrt[$productData['id']];

			$parentCat = $catRp->find($productData['parent']);

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

			if(!empty($this->modxImgPath)) {
				// работа с фотками
				$prodImg = isset($productProperties['cat_image']) ? $productProperties['cat_image'] : self::PRODUCT_IMG_THUMB;
				$prodImg = str_replace('/assets/images', '', $prodImg);
				$prodImg = str_replace('assets/images', '', $prodImg);

				if(isset($productProperties['cat_image_tn'])) {
					$prodImgTh = $productProperties['cat_image_tn'];
					$prodImgTh = str_replace('/assets/images', '', $prodImgTh);
					$prodImgTh = str_replace('assets/images', '', $prodImgTh);
				}

				if($prodImg != self::PRODUCT_IMG_THUMB) {
					if(file_exists($this->modxImgPath . $prodImg)) {
						$pathInfo = pathinfo($this->modxImgPath . $prodImg);
						copy($this->modxImgPath . $prodImg, $this->imgPath . '/gbi-photos/' . $productData['id'] . '.' . $pathInfo['extension']);
						if(!empty($prodImgTh)) {
							$pathInfoTh = pathinfo($this->modxImgPath . $prodImgTh);
							copy($this->modxImgPath . $prodImg, $this->imgPath . '/gbi-photos/' . $productData['id'] . '-th.' . $pathInfoTh['extension']);
						}
					} else {
						echo 'Image ' . $this->modxImgPath . $prodImg . ' for product ' . $productData['id'] . 'does not exist ' . "\n";
					}
				}
			}

			// сохранение сущности
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
				'/(\[|\{)\~\d+\~(\]|\})/',
				function($matches) {
					$matchInt = (int) preg_replace('/\D+/', '', $matches[0]);
					return $this->getPathForEntityId($matchInt);
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
				'/(\[|\{)\~\d+\~(\]|\})/',
				function($matches) {
					$matchInt = (int) preg_replace('/\D+/', '', $matches[0]);
					return $this->getPathForEntityId($matchInt);
				},
				$pageRow['content']
			);

			$content = str_replace('"images/', '"/assets/images/', $content);
			$content = str_replace('"assets/', '"/assets/', $content);
			$content = preg_replace('/href="[\/]*(?!assets)(?!http)([A-z1-9\/\-]+)\/"/', "href=\"{{path('app_main_staticpage', {'alias': '$1'})}}\"", $content);

			$staticPage = new StaticPage();
			$staticPage->setId($pageRow['id']);
			$staticPage->setTitle(str_replace('"images/', '"/assets/images/', $pageRow['longtitle']));
			$staticPage->setName(str_replace('"images/', '"/assets/images/', $pageRow['pagetitle']));
			$staticPage->setDescription($pageRow['description']);
			$staticPage->setAlias($pageRow['alias']);
			$staticPage->setText($content);
			$staticPage->setDate(date_create(date("Y-m-d H:i:s", $pageRow['createdon'])));
			$em->persist($staticPage);
		}
		$em->flush();
	}

	private function getPathForEntityId($entityId)
	{
		// Ищем среди статических страниц
		if(in_array($entityId, self::$staticPages)) {
			$alias = $this->pdo->query('SELECT alias FROM ' . self::MODX_SITE_CONTENT . ' WHERE id = ' . $entityId)->fetchColumn();
			return $this->getPathExpression('app_main_staticpage', array('alias' => $alias));
		}

		// Ищем среди обычных страниц (типа заказать прайсы итд)
		if(in_array($entityId, array_keys(self::$pages))) {
			return $this->getPathExpression(self::$pages[$entityId]);
		}

		// Ишем среди категорий
		$catRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Category');
		$category = $catRp->find($entityId);
		if(!empty($category)) {
			return $this->getPathExpression('app_catalog_category', array('id' => $entityId));
		}

		// Ищем среди продуктов
		$prodRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Product');
		$product = $prodRp->find($entityId);
		if(!empty($product)) {
			return $this->getPathExpression('app_catalog_product', array('id' => $entityId));
		}

		if(array_search($entityId, self::$ignoreIds) === false) {
			throw new \Exception("Invalid link to entity " . $entityId);
		};
	}

	private function getPathExpression($routeName, $args = array())
	{
		return '{{ path("' . $routeName . '"' . (($args) ? ', ' . json_encode($args) : "") . ') }}';
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
				'/(\[|\{)\~\d+\~(\]|\})/',
				function($matches) {
					$matchInt = (int) preg_replace('/\D+/', '', $matches[0]);
					return $this->getPathForEntityId($matchInt);
				},
				$obj['content']
			);

			$pregLinks = preg_replace_callback(
				'/href=(\"|\')\d+(\"|\')/',
				function($matches) {
					$matchInt = (int) preg_replace('/\D+/', '', $matches[0]);
					return 'href="' . $this->getPathForEntityId($matchInt) . '"';
				},
				$pregLinks
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