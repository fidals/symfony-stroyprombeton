<?php
namespace App\AdminBundle\Form\DataTransformer;

use App\MainBundle\Entity\Category;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\ORM\EntityManager;

class ParentTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Трансформирует объект Категории в строку с ее именем.
     *
     * @param $category
     * @return string
     */
    public function transform($category)
    {
        if (null === $category) {
            return '';
        }

        return $category->getName();
    }

    /**
     * Трансформирует имя Категории в соотв. объект.
     *
     * @param string $categoryName
     * @return Category|null
     * @throws TransformationFailedException если Entity не найдена
     */
    public function reverseTransform($categoryName)
    {
        if (!$categoryName) {
            return;
        }

        $category = $this->entityManager
            ->getRepository('AppMainBundle:Category')
            ->findOneByName($categoryName);

        if (null === $category) {
            throw new TransformationFailedException(sprintf(
                'Категории с именем "%s" не существует!',
                $categoryName
            ));
        }

        return $category;
    }
}
