<?php

namespace Duo\PartBundle\Configurator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractPartConfigurator implements PartConfiguratorInterface
{
	/**
	 * @var EventDispatcherInterface
	 */
	protected $eventDispatcher;

	/**
	 * @var Collection
	 */
	protected $configs;

	/**
	 * PartConfigurator constructor
	 *
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	public function __construct(EventDispatcherInterface $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;

		// dispatch pre load event
		$this->dispatchPreLoadEvent();
	}

	/**
	 * {@inheritDoc}
	 */
	public function addConfig(array $config): PartConfiguratorInterface
	{
		$this->getConfigs()->add($config);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeConfig(array $config): PartConfiguratorInterface
	{
		$this->getConfigs()->removeElement($config);

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfigs(): Collection
	{
		return $this->configs = $this->configs ?: new ArrayCollection();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTypes(bool $isView = false, array $ids = []): array
	{
		$types = [];

		foreach ($this->getConfigs() as $config)
		{
			foreach ($config['types'] as $id => $entry)
			{
				if (!empty($ids) && !in_array($id, $ids))
				{
					continue;
				}

				$types[$isView ? md5($entry['type']) : $entry['type']] = $entry['label'];
			}
		}

		return $types;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSections(bool $isView = false): array
	{
		$sections = [];

		foreach ($this->getConfigs() as $config)
		{
			foreach ($config['sections'] as $id => $entry)
			{
				$sections[$id] = [
					'label' => $entry['label'],
					'types' => array_keys($this->getTypes($isView, $entry['types']))
				];
			}
		}

		return $sections;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getIcons(bool $isView = false): array
	{
		$icons = [];

		foreach ($this->getConfigs() as $config)
		{
			foreach ($config['types'] as $entry)
			{
				if (empty($entry['icon']))
				{
					continue;
				}

				$icons[$isView ? md5($entry['type']) : $entry['type']] = $entry['icon'];
			}
		}

		return $icons;
	}
}
