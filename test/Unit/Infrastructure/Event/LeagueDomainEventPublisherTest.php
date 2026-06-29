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

namespace Webify\Base\Test\Unit\Infrastructure\Event;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Webify\Base\Domain\Event\DomainEventInterface;
use Webify\Base\Infrastructure\Event\LeagueDomainEventPublisher;

/**
 * LeagueDomainEventPublisherTest tests the functionality of the LeagueDomainEventPublisher class.
 *
 * @internal
 */
#[CoversClass(LeagueDomainEventPublisher::class)]
#[CoversMethod(LeagueDomainEventPublisher::class, 'publish')]
final class LeagueDomainEventPublisherTest extends TestCase
{
	/**
	 * Test that publish dispatches a single event through the PSR-14 dispatcher.
	 */
	#[Test]
	public function testPublishDispatchesEvent(): void
	{
		$event = self::createStub(DomainEventInterface::class);

		$dispatcher = $this->createMock(EventDispatcherInterface::class);
		$dispatcher->expects(self::once())->method('dispatch')->with($event);

		$publisher = new LeagueDomainEventPublisher($dispatcher);
		$publisher->publish($event);
	}

	/**
	 * Test that publish dispatches multiple events in sequence.
	 */
	#[Test]
	public function testPublishDispatchesMultipleEvents(): void
	{
		$event1 = self::createStub(DomainEventInterface::class);
		$event2 = self::createStub(DomainEventInterface::class);

		$dispatcher = $this->createMock(EventDispatcherInterface::class);
		$dispatcher->expects(self::exactly(2))->method('dispatch');

		$publisher = new LeagueDomainEventPublisher($dispatcher);
		$publisher->publish($event1, $event2);
	}

	/**
	 * Test that publish with no events does not call the dispatcher.
	 */
	#[Test]
	public function testPublishWithNoEvents(): void
	{
		$dispatcher = $this->createMock(EventDispatcherInterface::class);
		$dispatcher->expects(self::never())->method('dispatch');

		$publisher = new LeagueDomainEventPublisher($dispatcher);
		$publisher->publish();
	}

	/**
	 * Test that publish maintains the order of the dispatched events.
	 */
	#[Test]
	public function testPublishMaintainsOrder(): void
	{
		$event1 = self::createStub(DomainEventInterface::class);
		$event2 = self::createStub(DomainEventInterface::class);

		$invokedArgs = [];

		$dispatcher = $this->createMock(EventDispatcherInterface::class);
		$dispatcher
			->expects(self::exactly(2))
			->method('dispatch')
			->willReturnCallback(function ($event) use (&$invokedArgs): void {
				$invokedArgs[] = $event;
			})
		;

		$publisher = new LeagueDomainEventPublisher($dispatcher);
		$publisher->publish($event1, $event2);

		self::assertSame([$event1, $event2], $invokedArgs);
	}
}
