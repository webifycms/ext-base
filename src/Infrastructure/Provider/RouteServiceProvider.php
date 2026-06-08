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

namespace Webify\Base\Infrastructure\Provider;

use League\Route\Router;
use Psr\Container\ContainerInterface;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\BootstrapServiceProviderInterface;

/**
 * Route service provider register routes definitions.
 *
 * @todo This is an example for how to register routes, remove it.
 */
final readonly class RouteServiceProvider implements BootstrapServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function bootstrap(ContainerInterface $container): void
	{
		$config = $container->get(ConfigInterface::class);
		$router = $container->get(Router::class);
		$routes = require $config->configPath . '/routes.php';

		$routes($router);
	}
}
