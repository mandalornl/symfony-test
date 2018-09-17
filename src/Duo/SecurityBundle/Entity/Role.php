<?php

namespace Duo\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\CoreBundle\Entity\Property\IdTrait;
use Duo\CoreBundle\Entity\Property\TimestampTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(
 *     name="duo_role",
 *     uniqueConstraints={
 *		   @ORM\UniqueConstraint(name="role_uniq", columns={ "role" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="name_idx", columns={ "name" })
 *	   }
 * )
 * @ORM\Entity()
 * @UniqueEntity(fields={ "role" }, message="duo.security.errors.role_used")
 */
class Role implements RoleInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="role", type="string", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $role;

	/**
	 * {@inheritdoc}
	 */
	public function setName(?string $name): RoleInterface
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
	public function setRole(?string $role): RoleInterface
	{
		$this->role = $role;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRole(): ?string
	{
		return $this->role;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}