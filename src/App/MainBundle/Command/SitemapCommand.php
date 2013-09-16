<?php
namespace App\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class SitemapCommand extends ContainerAwareCommand
{
    const FILE_SITEMAP = '/var/www/stroyprombeton.ru/www/web/sitemap.xml';
    const URL_INDEX = 'http://www.stroyprombeton.ru';

    // you can modify this $repositories array for include some entities in sitemap
    // each entity MUST consist sitemapData() method
    public $repositories = array(
        'AppMainBundle:StaticPage',
        'AppCatalogBundle:Category',
        'AppCatalogBundle:Product'
    );

    // ugly urls
    public $baseCats = array(
        537 => 'prom-stroy',
        538 => 'dor-stroy',
        539 => 'ingener-stroy',
        540 => 'energy-stroy',
        541 => 'blag-territory',
        542 => 'neftegaz-stroy'
    );

    protected function configure()
    {
        $this->setName('sitemap:generate')
            ->setDescription('Generate sitemap');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityList = $this->generate($this->repositories);
        $sitemap = $this->getContainer()->get('templating')->render('AppMainBundle:Sitemap:sitemap.xml.twig', array(
            'entityList' => $entityList,
            'urlIndex'   => self::URL_INDEX
        ));
        if(file_put_contents(self::FILE_SITEMAP, $sitemap)) {
            die('sitemap successfully generated');
        } else {
            die('sorry, server error occured');
        }
    }

    public function generate($entityRepositories)
    {
        $entityList = array();
        $catRp = $this->getContainer()->get('doctrine')->getRepository('AppCatalogBundle:Category');
        foreach($entityRepositories as $repositoryName) {
            $rep = $this->getContainer()->get('doctrine')->getRepository($repositoryName);
            $entities = $rep->findAll();
            if($repositoryName == 'AppCatalogBundle:Category') {
                foreach($entities as $entity) {
                    $path = $catRp->getPath($entity);
                    if(!empty($path[0]) && !empty($this->baseCats[$path[0]->getId()])) {
                        $catUrl = $this->baseCats[$path[0]->getId()];
                    } else {
                        $catUrl = $this->baseCats[537];
                    }
                    $entityData = $entity->getSitemapData();
                    $entityData['locData']['parameters']['catUrl'] = $catUrl;
                    $entityList[] = $this->assemble($entityData);
                }
            } elseif($repositoryName == 'AppCatalogBundle:Product') {
                foreach($entities as $entity) {
                    $sectionId = $entity->getSectionId();
                    if(!empty($sectionId)) {
                        $path = $catRp->getPath($catRp->find($sectionId));
                        if(!empty($path[0]) && !empty($this->baseCats[$path[0]->getId()])) {
                            $catUrl = $this->baseCats[$path[0]->getId()];
                        } else {
                            $catUrl = $this->baseCats[537];
                        }
                    } else {
                        $catUrl = $this->baseCats[537];
                    }
                    $entityData = $entity->getSitemapData();
                    $entityData['locData']['parameters']['catUrl'] = $catUrl;
                    $entityList[] = $this->assemble($entityData);
                }
            } else {
                foreach($entities as $entity) {
                    $entityList[] = $this->assemble($entity->getSitemapData());
                }
            }
        }
        return $entityList;
    }

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
