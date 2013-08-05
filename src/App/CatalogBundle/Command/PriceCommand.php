<?php
namespace App\CatalogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Генерирует price.yml для market.yandex.ru
 * Вызывается как price:generate
 *
 * Class PriceCommand
 * @package App\MainBundle\Command
 */
class PriceCommand extends ContainerAwareCommand
{
    /**
     * Путь к файлу price.yml относительно root dir
     */
    const FILE_PRICE = '/../web/price.yml';

    protected function configure()
    {
        $this->setName('price:generate')
            ->setDescription('Generate price.yml');
    }

    /**
     * Выполняется при команде price:generate
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $prodRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Product');
        $catRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Category');

        $sitemap = $this->getContainer()->get('templating')->render('AppCatalogBundle:Price:price.yml.twig', array(
            'categoryList' => $catRp->findAll(),
            'productList'  => $prodRp->findAll(),
            'urlIndex'     => $this->getContainer()->getParameter('base_url')
        ));

        if(file_put_contents($this->getContainer()->get('kernel')->getRootDir() . self::FILE_PRICE, $sitemap)) {
            die('price successfully generated in ' . $this->getContainer()->get('kernel')->getRootDir() . self::FILE_PRICE);
        } else {
            die('sorry, server error occured');
        }
    }
}
