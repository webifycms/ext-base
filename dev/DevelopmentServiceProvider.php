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

namespace Webify\Base\Dev;

use Psr\Container\ContainerInterface;
use Webify\Base\Infrastructure\Contract\BootstrapServiceProviderInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Development service provider that registers the Whoops error handler only in development mode.
 */
final readonly class DevelopmentServiceProvider implements BootstrapServiceProviderInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function bootstrap(ContainerInterface $container): void
	{
		new Run()
			->pushHandler(new PrettyPageHandler())
			->register()
		;
	}
}
