<?php

namespace App\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * StaticPages
 *
 * @ORM\Table(name="static_pages")
 * @ORM\Entity
 */
class StaticPage
{
	use PageTrait;

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
	 * @ORM\Column(name="alias", type="string", length=255, nullable=true)
	 */
	private $alias;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_text_published", type="boolean", options={"default" = 0})
     */
    private $isTextPublished = true;

	public function __construct()
	{
		$this->date = new \DateTime();
	}

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
	 * Метод валидации для админки
	 * @param ExecutionContext $context
	 */
	public function validate(ExecutionContext $context)
	{
		$alias = $this->getAlias();
		if(preg_match("/[^a-z0-9_\/-]/", $alias)) {
			$context->addViolation('Недопустимые для ссылки символы');
		}
	}

	public function getRouteName()
	{
		return 'app_main_staticpage';
	}

	public function getRouteParameters()
	{
		if (empty($this->getAlias())){
			return null;
		}

		$parameters = array('alias' => $this->getAlias());

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
