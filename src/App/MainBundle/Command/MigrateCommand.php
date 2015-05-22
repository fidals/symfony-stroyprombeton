<?php
namespace App\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class MigrateCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this->setName('db:migrate')
			->setDescription('Migrate DB from modx to symfony2');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$dbName = $this->getContainer()->getParameter('database_name');
		$dbHost = $this->getContainer()->getParameter('database_name');
		$dsn = 'mysql:dbname=' . $dbName . ';host=' . $dbHost;
		$pdo = new \PDO(
			$dsn,
			$this->getContainer()->getParameter('database_user'),
			$this->getContainer()->getParameter('database_password')
		);

		$res = $pdo->query('SELECT * from stroyprombeton.static_pages')->fetchAll();
		var_dump($res);
	}
}