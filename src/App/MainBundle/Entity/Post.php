<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Posts
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="App\MainBundle\Entity\Repository\PostRepository")
 */
class Post
{
	use PageTrait;

	/**
	 * @var integer
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="intro_text", type="text", nullable=true)
	 */
	private $introText;

	public function __construct()
	{
		$this->date = new \DateTime();
	}

    /**
     * @var bool
     *
     * @ORM\Column(name="is_text_published", type="boolean", options={"default" = 0})
     */
    private $isTextPublished = true;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param string $introText
	 */
	public function setIntroText($introText)
	{
		$this->introText = $introText;
	}

	/**
	 * @return string
	 */
	public function getIntroText()
	{
		return $this->introText;
	}

	public function getRouteName()
	{
		return 'app_main_post';
	}

	public function getRouteParameters()
	{
		if (empty($this->getId())){
			return null;
		}

		$parameters = array('id' => $this->getId());

		return $parameters;
	}

    /**
     * @return boolean
     */
    public function isIsTextPublished()
    {
        return $this->isTextPublished;
    }

    /**
     * @param boolean $isTextPublished
     */
    public function setIsTextPublished($isTextPublished)
    {
        $this->isTextPublished = $isTextPublished;
    }
}
