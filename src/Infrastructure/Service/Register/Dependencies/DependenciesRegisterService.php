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

namespace Webify\Base\Infrastructure\Service\Register\Dependencies;

use Webify\Base\Infrastructure\Service\Register\PreRegisterServiceInterface;
use yii\di\Container;

/**
 * Abstract class, responsible for registering dependencies into a container.
 * This class should be extended by all classes that register dependencies and override the `getDependencies` method.
 */
abstract class DependenciesRegisterService implements PreRegisterServiceInterface, DependenciesRegisterServiceInterface
{
	final public function register(Container $container): void
	{
		$dependencies = $this->getDependencies();

		if (isset($dependencies['definitions'])) {
			$container->setDefinitions($dependencies['definitions']);
		}

		if (isset($dependencies['singletons'])) {
			$container->setSingletons($dependencies['singletons']);
		}
	}
}
