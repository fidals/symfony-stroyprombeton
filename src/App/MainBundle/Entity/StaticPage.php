<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaticPages
 *
 * @ORM\Table(name="static_pages")
 * @ORM\Entity
 */
class StaticPage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @!ORM\GeneratedValue(strategy="IDENTITY")-----
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published;

    /**
     * @var string
     *
     * @ORM\Column(name="introtext", type="text", nullable=true)
     */
    private $introtext;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="template", type="integer", nullable=false)
     */
    private $template;

    /**
     * @var string
     *
     * @ORM\Column(name="menutitle", type="string", length=255, nullable=false)
     */
    private $menutitle;

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $introtext
     */
    public function setIntrotext($introtext)
    {
        $this->introtext = $introtext;
        return $this;
    }

    /**
     * @return string
     */
    public function getIntrotext()
    {
        return $this->introtext;
    }

    /**
     * @param string $menutitle
     */
    public function setMenutitle($menutitle)
    {
        $this->menutitle = $menutitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getMenutitle()
    {
        return $this->menutitle;
    }

    /**
     * @param int $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @return int
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param int $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return int
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
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
                'route' => 'app_main_staticpage',
                'parameters' => array(
                    'alias' => $this->getAlias()
                )
            ),
            'priority'   => 0.9,
            'changefreq' => 'weekly'
        );
    }
}
