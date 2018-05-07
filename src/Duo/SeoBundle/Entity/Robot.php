<?php

namespace Duo\SeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\IdInterface;
use Duo\BehaviorBundle\Entity\IdTrait;
use Duo\BehaviorBundle\Entity\TimestampInterface;
use Duo\BehaviorBundle\Entity\TimestampTrait;

/**
 * @ORM\Table(name="duo_robot")
 * @ORM\Entity(repositoryClass="Duo\SeoBundle\Repository\RobotRepository")
 */
class Robot implements IdInterface, TimestampInterface
{
	use IdTrait;
	use TimestampTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="content", type="text", nullable=true)
	 */
	private $content;

	/**
	 * Set content
	 *
	 * @param string $content
	 *
	 * @return Robot
	 */
	public function setContent(string $content = null): Robot
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * Get content
	 *
	 * @return string
	 */
	public function getContent(): ?string
	{
		return $this->content;
	}
}