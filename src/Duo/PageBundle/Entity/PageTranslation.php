<?php

namespace Duo\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Duo\BehaviorBundle\Entity\PublishInterface;
use Duo\BehaviorBundle\Entity\PublishTrait;
use Duo\BehaviorBundle\Entity\SlugInterface;
use Duo\BehaviorBundle\Entity\SlugTrait;
use Duo\BehaviorBundle\Entity\UrlInterface;
use Duo\BehaviorBundle\Entity\UrlTrait;
use Duo\NodeBundle\Entity\AbstractNodeTranslation;
use Duo\PartBundle\Entity\EntityPartInterface;
use Duo\PartBundle\Entity\EntityPartTrait;
use Duo\SeoBundle\Entity\SeoInterface;
use Duo\SeoBundle\Entity\SeoTrait;

/**
 * @ORM\Table(name="page_translation")
 * @ORM\Entity()
 */
class PageTranslation extends AbstractNodeTranslation implements SlugInterface, UrlInterface, PublishInterface, SeoInterface, EntityPartInterface
{
	use SlugTrait;
	use UrlTrait;
	use PublishTrait;
	use SeoTrait;
	use EntityPartTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="title", type="string", nullable=true)
	 */
	protected $title;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="visible_menu", type="boolean", options={ "default" = 1 })
	 */
	protected $visibleMenu = true;

	/**
	 * Set title
	 *
	 * @param string $title
	 *
	 * @return PageTranslation
	 */
	public function setTitle(string $title = null): PageTranslation
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle(): ?string
	{
		return $this->title;
	}

	/**
	 * Set visibleMenu
	 *
	 * @param bool $visibleMenu
	 *
	 * @return PageTranslation
	 */
	public function setVisibleMenu(bool $visibleMenu = true): PageTranslation
	{
		$this->visibleMenu = $visibleMenu;

		return $this;
	}

	/**
	 * Get visibleMenu
	 *
	 * @return bool
	 */
	public function getVisibleMenu(): bool
	{
		return $this->visibleMenu;
	}

	/**
	 * Get value to slugify
	 *
	 * @return string
	 */
	public function getValueToSlugify(): string
	{
		return $this->title;
	}

	/**
	 * Get value to urlize
	 *
	 * @return string
	 */
	public function getValueToUrlize(): string
	{
		return $this->slug;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPartReferenceClass(): string
	{
		return PagePartReference::class;
	}
}