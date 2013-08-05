<?php
namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\CatalogBundle\Extension\Transliteration;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity
 */

class News
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
     * @ORM\Column(name="header", type="string", length=200, nullable=false)
     */
    private $header;
    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable=true)
     */
    private $alias;
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set header
     *
     * @param string $header
     * @return News
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string 
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return News
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set introtext
     *
     * @param string $introtext
     * @return News
     */
    public function setIntrotext($introtext)
    {
        $this->introtext = $introtext;

        return $this;
    }

    /**
     * Get introtext
     *
     * @return string 
     */
    public function getIntrotext()
    {
        return $this->introtext;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return News
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return News
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
}
