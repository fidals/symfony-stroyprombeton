<?php
namespace App\YandexMarketBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда для генерации yml файла для Yandex Market
 * Class YmlDumpCommand
 * @package App\YandexMarketBundle\Command
 */
class YmlDumpCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('market:yml:dump')
			->setDescription('Dump yml for Yandex Market');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$generator = $this->getContainer()->get('yandex_market.generator');
		$yml = $generator->generate();
//		$yml = str_replace(array("\r","\n", "\t"), "", $yml);
		$fname = $this->getContainer()->getParameter('app_yandex_market.filename');
		if(empty($fname)) {
			throw new Exception('Configuration for app_yandex_market.filename not provided');
		}
		file_put_contents('web/' . $this->getContainer()->getParameter('app_yandex_market.filename') . '.xml', $yml);
	}
}