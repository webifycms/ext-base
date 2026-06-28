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

namespace Webify\Base\Infrastructure\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Webify\Base\Domain\Event\{DomainEventInterface, DomainEventPublisherInterface};

/**
 * Implementation of the DomainEventPublisherInterface publishes domain events through the League Event dispatcher.
 */
final readonly class LeagueDomainEventPublisher implements DomainEventPublisherInterface
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private EventDispatcherInterface $dispatcher
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function publish(DomainEventInterface ...$events): void
	{
		foreach ($events as $event) {
			$this->dispatcher->dispatch($event);
		}
	}
}
