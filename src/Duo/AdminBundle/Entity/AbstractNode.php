<?php

namespace Duo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\AdminBundle\Entity\Behavior\NodeInterface;
use Duo\AdminBundle\Entity\Behavior\TaxonomyInterface;
use Duo\AdminBundle\Entity\Behavior\TaxonomyTrait;
use Duo\BehaviorBundle\Entity\CloneTrait;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\SoftDeleteTrait;
use Duo\BehaviorBundle\Entity\SortTrait;
use Duo\BehaviorBundle\Entity\TimeStampTrait;
use Duo\BehaviorBundle\Entity\TranslateTrait;
use Duo\BehaviorBundle\Entity\VersionTrait;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractNode implements NodeInterface, TaxonomyInterface
{
	use IdTrait;
	use TaxonomyTrait;
	use SoftDeleteTrait;
	use TranslateTrait;
	use SortTrait;
	use CloneTrait;
	use VersionTrait;
	use TimeStampTrait;

    /**
     * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=false)
	 * @Assert\NotBlank()
     */
    protected $name;

    /**
     * {@inheritdoc}
     */
    public function setName(string $name = null): NodeInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}