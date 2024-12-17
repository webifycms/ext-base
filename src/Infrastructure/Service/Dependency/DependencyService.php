<?php
/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service\Dependency;

use Webify\Base\Domain\Service\Dependency\DependencyServiceInterface;
use yii\di\Container;

/**
 * Represents a service responsible for handling application dependencies.
 *
 * This class implements the `DependencyServiceInterface` and manages
 * a dependency injection container to provide services and their dependencies.
 */
final class DependencyService implements DependencyServiceInterface
{
	private readonly Container $container;

	/**
	 * The class constructor.
	 */
	public function __construct()
	{
		// gets the framework container
		$this->container = \Yii::$container;
		// the class itself registers to the container
		$this->container->setSingleton(DependencyServiceInterface::class, $this);
	}

    /**
     * @inheritDoc
     */
	public function getContainer(): Container
	{
		return $this->container;
	}
}
