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

namespace Webify\Base\Infrastructure\Contract;

use Psr\Container\ContainerInterface;

/**
 * BootstrapServiceProviderInterface defines the contract for a service provider
 * that can be used in the bootstrapping process of the application.
 *
 * `bootstrap()` runs after the container is built — register routes, middleware,
 *  console commands, and event listeners here, etc.
 */
interface BootstrapServiceProviderInterface
{
	/**
	 * Provider's bootstrap that will be called on the application bootstrapping process.
	 *
	 * @param ContainerInterface $container the application container
	 */
	public function bootstrap(ContainerInterface $container): void;
}
