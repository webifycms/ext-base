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

use League\Event\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Webify\Base\Domain\Event\DomainEventPublisherInterface;
use Webify\Base\Infrastructure\Contract\DefinitionsInterface;
use Webify\Base\Infrastructure\Event\LeagueDomainEventPublisher;

use function DI\{create, get};

/**
 * The eventing definitions: the PSR-14 dispatcher and the domain event
 * publisher that bridges the domain contract to the League dispatcher.
 */
final class EventDefinitions implements DefinitionsInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function getDefinitions(): array
	{
		return [
			EventDispatcher::class               => create(EventDispatcher::class),
			EventDispatcherInterface::class      => get(EventDispatcher::class),
			DomainEventPublisherInterface::class => create(LeagueDomainEventPublisher::class)
				->constructor(get(EventDispatcherInterface::class)),
		];
	}
}
