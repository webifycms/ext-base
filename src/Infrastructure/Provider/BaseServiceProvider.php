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
use Webify\Base\Infrastructure\Contract\{BootstrapServiceProviderInterface, ServiceProviderInterface};
use Webify\Base\Infrastructure\Presentation\Http\Middleware\ErrorHandler;

/**
 * Base service provider.
 */
final readonly class BaseServiceProvider implements ServiceProviderInterface, BootstrapServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getDefinitions(): array
	{
		return require __DIR__ . '/../definitions.php';
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap(ContainerInterface $container): void
	{
		/** @var Router $router */
		$router = $container->get(Router::class);

		/** @var ErrorHandler $errorHandler */
		$errorHandler = $container->get(ErrorHandler::class);

		$router->middleware($errorHandler);
	}
}
