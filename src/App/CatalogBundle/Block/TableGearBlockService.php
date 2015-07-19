<?php

namespace App\CatalogBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;

class TableGearBlockService extends BaseBlockService
{
	public function getName()
	{
		return 'TableGear Editor';
	}

	public function getDefaultSettings()
	{
		return array();
	}

	public function validateBlock(ErrorElement $errorElement, BlockInterface $block) {}

	public function buildEditForm(FormMapper $formMapper, BlockInterface $block) {}

	public function execute(BlockContextInterface $blockContext, Response $response = null)
	{
		return $this->renderResponse('AppCatalogBundle:Block:button.tablegear.html.twig', array(
			'block' => $blockContext->getBlock(),
			'settings' => $blockContext->getSettings()
		), $response);
	}
}
