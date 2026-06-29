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

namespace Webify\Base\Domain\Entity;

use Webify\Base\Domain\Event\DomainEventInterface;

/**
 * The aggregate root base class.
 */
abstract class AggregateRoot
{
	/**
	 * The recorded domain events list.
	 *
	 * @var DomainEventInterface[]
	 */
	private array $domainEvents = [];

	/**
	 * Get domain events.
	 *
	 * @return DomainEventInterface[]
	 */
	public function getDomainEvents(): array
	{
		return $this->domainEvents;
	}

	/**
	 * Clear all the domain events.
	 *
	 * @return DomainEventInterface[]
	 */
	public function releaseDomainEvents(): array
	{
		$events               = $this->domainEvents;
		$this->domainEvents   = [];

		return $events;
	}

	/**
	 * Record an event.
	 */
	protected function recordDomainEvent(DomainEventInterface $event): void
	{
		$this->domainEvents[] = $event;
	}
}
