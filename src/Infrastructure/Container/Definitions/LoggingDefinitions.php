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

use Monolog\Formatter\JsonFormatter;
use Monolog\{Handler\RotatingFileHandler, Level, Logger};
use Psr\Log\LoggerInterface;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\DefinitionsInterface;
use Webify\Base\Infrastructure\Environment\Environment;

use function DI\factory;

/**
 * The logging definitions: a JSON-on-disk PSR-3 logger whose verbosity is
 * determined by the environment's debug flag.
 */
final class LoggingDefinitions implements DefinitionsInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getDefinitions(): array
	{
		return [
			LoggerInterface::class => factory(
				static function (ConfigInterface $config, Environment $environment): Logger {
					$handler = new RotatingFileHandler(
						$config->logPath . '/app.log',
						7,
						$environment->isDebugEnabled() ? Level::Debug : Level::Warning,
					);
					$handler->setFormatter(new JsonFormatter());

					return new Logger($config->get('id'))->pushHandler($handler);
				}
			),
		];
	}
}
