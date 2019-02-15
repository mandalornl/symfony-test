<?php

namespace Duo\MediaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class DuoMediaExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
    	$configuration = new Configuration();
    	$config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('duo_media.relative_path', $config['relative_path']);
        $container->setParameter('duo_media.absolute_path', $config['absolute_path']);
        $container->setParameter('duo_media.cache_prefix', $config['cache_prefix']);
    }

	/**
	 * {@inheritdoc}
	 */
	public function prepend(ContainerBuilder $container): void
	{
		$config = Yaml::parseFile(__DIR__ . '/../Resources/config/liip_imagine_filters.yml');

		$container->prependExtensionConfig('liip_imagine', $config['liip_imagine']);
	}
}
