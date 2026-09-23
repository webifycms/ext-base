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

use Laminas\HttpHandlerRunner\Emitter\{EmitterInterface, SapiEmitter};
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\{ServerRequestCreator, ServerRequestCreatorInterface};
use Psr\Container\ContainerInterface;
use Psr\Http\Message\{
	RequestFactoryInterface,
	ResponseFactoryInterface,
	ServerRequestFactoryInterface,
	StreamFactoryInterface,
	UploadedFileFactoryInterface,
	UriFactoryInterface
};
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Contract\EscaperInterface;
use Webify\Base\Infrastructure\Contract\{BaseUrlInterface, DefinitionsInterface, UrlGeneratorInterface};
use Webify\Base\Infrastructure\Helper\Escaper;
use Webify\Base\Infrastructure\Kernel\Http;
use Webify\Base\Infrastructure\Service\{BaseUrl, UrlGenerator};

use function DI\{autowire, create, factory, get};

/**
 * The HTTP stack definitions: PSR-17 factories, the request creator, router,
 * response emitter, and the HTTP kernel.
 */
final class HttpDefinitions implements DefinitionsInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getDefinitions(): array
	{
		return [
			// The common HTML escaper shared by every renderer and helper.
			EscaperInterface::class              => create(Escaper::class),
			// Nyholm\Psr17Factory implements all PSR-17 factory interfaces in
			// one class. Each interface resolves to the same shared instance.
			Psr17Factory::class                  => create(Psr17Factory::class),
			RequestFactoryInterface::class       => get(Psr17Factory::class),
			ResponseFactoryInterface::class      => get(Psr17Factory::class),
			ServerRequestFactoryInterface::class => get(Psr17Factory::class),
			StreamFactoryInterface::class        => get(Psr17Factory::class),
			UriFactoryInterface::class           => get(Psr17Factory::class),
			UploadedFileFactoryInterface::class  => get(Psr17Factory::class),
			// Explicit constructor wiring because all four parameters share
			// the same concrete type (Psr17Factory) despite resolving to
			// different interfaces.
			ServerRequestCreatorInterface::class => create(ServerRequestCreator::class)
				->constructor(
					get(ServerRequestFactoryInterface::class),
					get(UriFactoryInterface::class),
					get(UploadedFileFactoryInterface::class),
					get(StreamFactoryInterface::class),
				),
			// Route definitions and middleware are added during bootstrapping.
			Router::class                        => factory(
				static function (ContainerInterface $container): Router {
					$strategy = new ApplicationStrategy();
					$router   = new Router();

					$strategy->setContainer($container);
					$router->setStrategy($strategy);

					return $router;
				}
			),
			EmitterInterface::class              => create(SapiEmitter::class),
			'httpKernel'                         => autowire(Http::class),
			// BaseUrl configuration and Url generator
			BaseUrlInterface::class              => factory(
				static fn (ConfigInterface $config) => BaseUrl::fromConfig($config)
			),
			UrlGeneratorInterface::class         => create(UrlGenerator::class)
				->constructor(
					get(Router::class),
					get(BaseUrlInterface::class)
				),
		];
	}
}
