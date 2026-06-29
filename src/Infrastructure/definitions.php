<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

use Laminas\HttpHandlerRunner\Emitter\{EmitterInterface, SapiEmitter};
use League\Event\EventDispatcher;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Monolog\Formatter\JsonFormatter;
use Monolog\{Handler\RotatingFileHandler, Level, Logger};
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\{ServerRequestCreator, ServerRequestCreatorInterface};
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\{
	RequestFactoryInterface,
	ResponseFactoryInterface,
	ServerRequestFactoryInterface,
	StreamFactoryInterface,
	UploadedFileFactoryInterface,
	UriFactoryInterface
};
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Domain\Event\DomainEventPublisherInterface;
use Webify\Base\Infrastructure\Environment\Environment;
use Webify\Base\Infrastructure\Event\LeagueDomainEventPublisher;
use Webify\Base\Infrastructure\Kernel\{Console, Http};

use function DI\{autowire, create, factory, get};

return [
	// -------------------------------------------------------------------------
	// PSR-17 HTTP Factories
	// Nyholm\Psr17Factory implements all PSR-17 factory interfaces in one class.
	// Each interface resolves to the same shared instance.
	// -------------------------------------------------------------------------
	Psr17Factory::class                         => create(Psr17Factory::class),
	RequestFactoryInterface::class              => get(Psr17Factory::class),
	ResponseFactoryInterface::class             => get(Psr17Factory::class),
	ServerRequestFactoryInterface::class        => get(Psr17Factory::class),
	StreamFactoryInterface::class               => get(Psr17Factory::class),
	UriFactoryInterface::class                  => get(Psr17Factory::class),
	UploadedFileFactoryInterface::class         => get(Psr17Factory::class),

	// -------------------------------------------------------------------------
	// PSR-7 Server Request Creator
	// Explicit constructor wiring because all four parameters share the same
	// concrete type (Psr17Factory) despite resolving to different interfaces.
	// -------------------------------------------------------------------------
	ServerRequestCreatorInterface::class        => create(ServerRequestCreator::class)
		->constructor(
			get(ServerRequestFactoryInterface::class),
			get(UriFactoryInterface::class),
			get(UploadedFileFactoryInterface::class),
			get(StreamFactoryInterface::class),
		),

	// -------------------------------------------------------------------------
	// HTTP Router
	// league/route — route definitions and middleware are added during bootstrapping.
	// -------------------------------------------------------------------------
	Router::class                               => factory(
		static function (ContainerInterface $container): Router {
			$strategy = new ApplicationStrategy();
			$router   = new Router();

			$strategy->setContainer($container);
			$router->setStrategy($strategy);

			return $router;
		}
	),

	// -------------------------------------------------------------------------
	// Response Emitter
	// -------------------------------------------------------------------------
	EmitterInterface::class                     => create(SapiEmitter::class),

	// -------------------------------------------------------------------------
	// PSR-3 Logger
	// JSON format on disk; verbosity determined by the debug flag.
	// -------------------------------------------------------------------------
	LoggerInterface::class                      => factory(
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

	// -------------------------------------------------------------------------
	// PSR-14 Event Dispatcher
	// -------------------------------------------------------------------------
	EventDispatcher::class                      => create(EventDispatcher::class),
	EventDispatcherInterface::class             => get(EventDispatcher::class),

	// -------------------------------------------------------------------------
	// Domain Event Publisher
	// Bridges the domain contract to the League dispatcher infrastructure.
	// -------------------------------------------------------------------------
	DomainEventPublisherInterface::class        => create(LeagueDomainEventPublisher::class)
		->constructor(get(EventDispatcherInterface::class)),

	// -------------------------------------------------------------------------
	// Symfony Console Application
	// Commands are added during each provider's boot() phase.
	// -------------------------------------------------------------------------
	ConsoleApplication::class                   => factory(
		static function (ConfigInterface $config, ContainerInterface $container): ConsoleApplication {
			return new ConsoleApplication(
				$config->get('name', 'WebifyCMS'),
				$config->get('version', '0.0.1'),
				$container
			);
		}
	),

	// -------------------------------------------------------------------------
	// Kernels
	// -------------------------------------------------------------------------
	'httpKernel'                                => autowire(Http::class),
	'consoleKernel'                             => autowire(Console::class),
];
