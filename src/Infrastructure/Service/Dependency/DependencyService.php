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
 * DependencyService.
 */
final class DependencyService implements DependencyServiceInterface
{
	private readonly Container $container;

	/**
	 * DependencyContainer constructor.
	 */
	public function __construct()
	{
		// gets the framework container
		$this->container = \Yii::$container;
		// the class itself registers to the container
		$this->container->setSingleton(DependencyServiceInterface::class, $this);
	}

	public function getContainer(): Container
	{
		return $this->container;
	}
}
