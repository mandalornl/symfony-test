<?php

namespace AppBundle\Entity\PagePart;

use AppBundle\Form\PagePart\TextPagePartType;
use Doctrine\ORM\Mapping as ORM;
use Duo\PageBundle\Entity\AbstractPagePart;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="duo_page_part_text")
 * @ORM\Entity()
 */
class TextPagePart extends AbstractPagePart
{
	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="text", nullable=true)
	 * @Assert\NotBlank()
	 */
	protected $value;

	/**
	 * {@inheritdoc}
	 */
	public function getPartFormType(): string
	{
		return TextPagePartType::class;
	}

	/**
	 * Get view
	 *
	 * @return string
	 */
	public function getView(): string
	{
		return '@App/PagePart/Text/view.html.twig';
	}
}