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

namespace Webify\Base\Test\Unit\Domain\Entity;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Webify\Base\Domain\Entity\AggregateRoot;
use Webify\Base\Domain\Event\DomainEventInterface;
use Webify\Base\Test\Unit\Domain\Entity\Fixture\TestableAggregateRoot;

/**
 * AggregateRootTest tests the functionality of the AggregateRoot class.
 *
 * @internal
 */
#[CoversClass(AggregateRoot::class)]
#[CoversMethod(AggregateRoot::class, 'recordDomainEvent')]
#[CoversMethod(AggregateRoot::class, 'getDomainEvents')]
#[CoversMethod(AggregateRoot::class, 'releaseDomainEvents')]
final class AggregateRootTest extends TestCase
{
	/**
	 * Test record domain events.
	 */
	#[Test]
	public function testRecordEvents(): void
	{
		$aggregateRoot = new TestableAggregateRoot();

		$event1 = self::createStub(DomainEventInterface::class);
		$event2 = self::createStub(DomainEventInterface::class);

		$aggregateRoot->recordDomainEvent($event1);
		$aggregateRoot->recordDomainEvent($event2);

		$events = $aggregateRoot->getDomainEvents();

		self::assertCount(2, $events);
		self::assertSame($event1, $events[0]);
		self::assertSame($event2, $events[1]);
	}

	/**
	 * Test clear domain events.
	 */
	#[Test]
	public function testReleaseEvents(): void
	{
		$aggregateRoot = new TestableAggregateRoot();

		$event1 = self::createStub(DomainEventInterface::class);
		$event2 = self::createStub(DomainEventInterface::class);

		$aggregateRoot->recordDomainEvent($event1);
		$aggregateRoot->recordDomainEvent($event2);

		$events = $aggregateRoot->releaseDomainEvents();

		self::assertCount(2, $events);
		self::assertSame($event1, $events[0]);
		self::assertSame($event2, $events[1]);

		// Ensure events are cleared after release
		self::assertEmpty($aggregateRoot->getDomainEvents());
	}

	/**
	 * Test release events return empty when no events were recorded.
	 */
	#[Test]
	public function testReleaseEventsReturnsEmptyArrayWhenNoEventsRecorded(): void
	{
		$aggregateRoot = new TestableAggregateRoot();
		$events        = $aggregateRoot->releaseDomainEvents();

		self::assertEmpty($events);
	}
}
