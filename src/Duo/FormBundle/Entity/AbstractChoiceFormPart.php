<?php

namespace Duo\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractChoiceFormPart extends AbstractTextFormPart implements ChoiceFormPartInterface
{
	/**
	 * @var bool
	 *
	 * @ORM\Column(name="expanded", type="boolean", options={ "default" = 0})
	 */
	protected $expanded = false;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="multiple", type="boolean", options={ "default" = 0})
	 */
	protected $multiple = false;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="choices", type="text", nullable=false)
	 * @Assert\NotBlank()
	 */
	protected $choices;

	/**
	 * {@inheritdoc}
	 */
	public function setExpanded(bool $expanded = false): ChoiceFormPartInterface
	{
		$this->expanded = $expanded;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getExpanded(): bool
	{
		return $this->expanded;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMultiple(bool $multiple = false): ChoiceFormPartInterface
	{
		$this->multiple = $multiple;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMultiple(): bool
	{
		return $this->multiple;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setChoices(string $choices = null): ChoiceFormPartInterface
	{
		$this->choices = $choices;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getChoices(): ?string
	{
		return $this->choices;
	}
}