<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Container\Definitions;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\DefinitionsInterface;
use Webify\Base\Infrastructure\Kernel\Console;

use function DI\{autowire, factory};

/**
 * The console definitions: the Symfony console application and the console
 * kernel the commands are added to during each provider's boot() phase.
 */
final class ConsoleDefinitions implements DefinitionsInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getDefinitions(): array
	{
		return [
			ConsoleApplication::class => factory(
				static function (ConfigInterface $config, ContainerInterface $container): ConsoleApplication {
					return new ConsoleApplication(
						$config->get('name', 'WebifyCMS'),
						$config->get('version', '0.0.1'),
						$container
					);
				}
			),
			'consoleKernel'           => autowire(Console::class),
		];
	}
}
