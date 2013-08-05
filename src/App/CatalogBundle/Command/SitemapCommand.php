<?php
namespace App\CatalogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * Генерирует sitemap.xml
 * Вызывается как sitemap:generate
 *
 * Class SitemapCommand
 * @package App\MainBundle\Command
 */
class SitemapCommand extends ContainerAwareCommand
{
    /**
     * Путь к файлу sitemap.xml относительно root dir
     */
    const FILE_SITEMAP = '/../web/sitemap.xml';

    /**
     * Можно изменить $repositories для добавления сущностей в sitemap
     * каждая сущность включаемая в sitemap ДОЛЖНА иметь метод getSitemapData() особого вида (см другие сущнсти)
     *
     * @var array
     */
    public $repositories = array(
        'AppMainBundle:StaticPage',
        'AppCatalogBundle:Category',
        'AppCatalogBundle:Product',
    );

    protected function configure()
    {
        $this->setName('sitemap:generate')
            ->setDescription('Generate sitemap');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityList = $this->generate($this->repositories);
        $sitemap = $this->getContainer()->get('templating')->render('AppCatalogBundle:Sitemap:sitemap.xml.twig', array(
            'entityList' => $entityList,
            'urlIndex'   => $this->getContainer()->getParameter('base_url')
        ));

        if(file_put_contents($this->getContainer()->get('kernel')->getRootDir() . self::FILE_SITEMAP, $sitemap)) {
            die('sitemap successfully generated');
        } else {
            die('sorry, server error occured');
        }
    }

    /**
     * Выполняется при команде sitemap:generate
     *
     * @param $entityRepositories
     * @return array
     */
    public function generate($entityRepositories)
    {
        $entityList = array();
        foreach($entityRepositories as $repositoryName) {
            $rep = $this->getContainer()->get('doctrine')->getRepository($repositoryName);
            $entities = $rep->findAll();
            foreach($entities as $entity) {
                $entityList[] = $this->assemble($entity->getSitemapData());
            }
        }
        return $entityList;
    }

    /**
     * Возвращает сформированный массив данных для каждой сущности в sitemap
     * на вход принимает $entity->getSitemapData()
     *
     * @param $entityData
     * @return array
     */
    public function assemble($entityData)
    {
        return array(
            'loc'        => (empty($entityData['loc'])) ? $this->getContainer()->get('router')->generate($entityData['locData']['route'], $entityData['locData']['parameters'], false) : $entityData['loc'],
            'lastmod'    => (empty($entityData['lastmod'])) ? date("Y-m-d H:i:s") : $entityData['lastmod'],
            'changefreq' => (empty($entityData['changefreq'])) ? 'weekly' : $entityData['changefreq'],
            'priority'   => (empty($entityData['priority'])) ? '1' : $entityData['priority'],
        );
    }

}