<?php

namespace Duo\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class TestExtension extends AbstractExtension
{
	/**
	 * {@inheritDoc}
	 */
	public function getTests(): array
	{
		return [
			new TwigTest('instanceof', [$this, 'isInstanceOf']),
			new TwigTest('bool', [$this, 'isBool']),
		];
	}

	/**
	 * Check whether or not value is instance of class
	 *
	 * @param mixed $value
	 * @param string $className
	 *
	 * @return bool
	 *
	 * @throws \Throwable
	 */
	public function isInstanceOf($value, string $className): bool
	{
		return is_object($value) && (new \ReflectionClass($className))->isInstance($value);
	}

	/**
	 * Check whether or not value is boolean
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function isBool($value): bool
	{
		return in_array($value, [1, 0, true, false, '1', '0', 'true', 'false'], true);
	}
}
