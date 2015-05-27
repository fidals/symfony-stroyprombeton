<?php
namespace App\MainBundle\Command;

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
	const MODX_DB_NAME = 'shopelecru_pb';

	protected function configure()
	{
		$this->setName('db:migrate')
			->setDescription('Migrate DB from modx to symfony2');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$spRp = $this->getContainer()->get('doctrine')->getRepository('AppMainBundle:StaticPage');

		$dbHost = $this->getContainer()->getParameter('database_host');
		$dsn = 'mysql:dbname=' . self::MODX_DB_NAME . ';host=' . $dbHost;

		$pdo = new \PDO(
			$dsn,
			$this->getContainer()->getParameter('database_user'),
			$this->getContainer()->getParameter('database_password')
		);
		$pdo->exec('SET NAMES utf8');

		$testQuery = 'SELECT * from `modx_categories`';
		$res1 = $pdo->query($testQuery)->fetchAll();
		$res2 = $spRp->findAll();
		var_dump($res2[0], $res1[0]);
	}
}