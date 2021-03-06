<?php

namespace Duo\FormBundle\Entity\FormPart;

use Doctrine\ORM\Mapping as ORM;
use Duo\FormBundle\Form\FormPart\TextFormPartType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @ORM\Table(name="duo_form_part_text")
 * @ORM\Entity()
 */
class TextFormPart extends AbstractTextFormPart
{
	/**
	 * {@inheritDoc}
	 */
	public function getFormType(): string
	{
		return TextType::class;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPartFormType(): string
	{
		return TextFormPartType::class;
	}
}
