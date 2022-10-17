<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Dependency;

use OneCMS\Base\Domain\Service\Dependency\DependencyServiceInterface;
use Yii;
use yii\di\Container;

/**
 * DependencyService.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 */
class DependencyService implements DependencyServiceInterface
{
	private readonly Container $container;

	/**
	 * DependencyContainer constructor.
	 */
	public function __construct()
	{
		// gets the framework container
		$this->container = Yii::$container;
		// the class itself registers to the container
		$this->container->setSingleton(DependencyServiceInterface::class, $this);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getContainer(): Container
	{
		return $this->container;
	}
}
