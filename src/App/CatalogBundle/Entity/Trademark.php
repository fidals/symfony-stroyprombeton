<?php

namespace App\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\CatalogBundle\Extension\Transliteration;

/**
 * Trademark
 *
 * @ORM\Table(name="trademark")
 * @ORM\Entity
 */
class Trademark
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=200, nullable=false)
     */
    private $alias;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=400, nullable=false)
     */
    private $title;
    /**
     * @ORM\OneToMany(targetEntity="Series", mappedBy="trademark")
     */
    protected $series;
    public function __construct()
    {
        $this->series = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }
    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }
    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    public function getSitemapData()
    {
        return array(
            'locData' => array(
                'route' => 'app_catalog_explore',
                'parameters' => array(
                    'catalogUnitName' => Transliteration::get($this->getTitle())
                )
            ),
            'priority'   => 0.8,
            'changefreq' => 'weekly'
        );
    }

    /**
     * Add series
     *
     * @param \App\CatalogBundle\Entity\Series $series
     * @return Trademark
     */
    public function addSeries(\App\CatalogBundle\Entity\Series $series)
    {
        $this->series[] = $series;

        return $this;
    }

    /**
     * Remove series
     *
     * @param \App\CatalogBundle\Entity\Series $series
     */
    public function removeSeries(\App\CatalogBundle\Entity\Series $series)
    {
        $this->series->removeElement($series);
    }

    /**
     * Get series
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeries()
    {
        return $this->series;
    }
    public function __toString()
    {
        return (string)$this->getAlias();
    }
}
