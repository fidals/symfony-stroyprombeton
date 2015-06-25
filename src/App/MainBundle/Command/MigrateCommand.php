<?php
namespace App\MainBundle\Command;

use App\CatalogBundle\Entity\Category;
use App\MainBundle\Entity\Post;
use App\MainBundle\Entity\StaticPage;
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

	/**
	 * Идентификатор корня новостей
	 */
	const ROOT_POST_ID = 12526;


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
		'AppMainBundle:Post',
		'AppMainBundle:StaticPage'
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
		$this->migrateCategories(self::ROOT_CATEGORY_ID);
		$this->migratePosts();
		$this->migrateStaticPages();
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

//				if(isset($properties['xml_id'])) {
//					$category->setId((int) $properties['xml_id']);
//				} else {
					$category->setId((int) $row['id']);
//				}

				if($parentCategory) {
					$category->setParent($parentCategory);
				}

				$em->persist($category);
				$em->flush();

				$this->migrateCategories($row['id'], $category);
			}
		}
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
		$staticPages = array(3, 4, 7, 8, 9, 624, 12435);
		$baseCats = array(
			456 => 'prom-stroy',
			457 => 'dor-stroy',
			458 => 'ingener-stroy',
			459 => 'energy-stroy',
			460 => 'blag-territory',
			461 => 'neftegaz-stroy'
		);

		if(array_search($entityId, $staticPages) !== false) {
			$alias = $this->pdo->query('SELECT alias FROM ' . self::MODX_SITE_CONTENT . ' WHERE id = ' . $entityId)->fetchColumn();
			$routeName = 'app_main_staticpage';
			$args = array('alias' => $alias);
		} else {
			$catRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Category');
			$category = $catRp->find($entityId);
			if(!empty($category)) {
				$rootCategoryUrl = $baseCats[$catRp->getPath($category)[0]->getId()];
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
					$productCatUrl = $baseCats[$catRp->getPath($product->getCategory())[0]->getId()];
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
				throw new \Exception('Invalid link to entity');
			};
		}
	}

	private function findChildren($categoryId)
	{
		$query = 'SELECT * FROM ' . self::MODX_SITE_CONTENT . ' as a WHERE a.isfolder = 1 AND a.parent = ' . $categoryId;
		return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}

	// Эти два метода ниже пришли сюда из dev-274
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