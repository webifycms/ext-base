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

namespace Webify\Base\Test\Unit\Infrastructure\Container\Definitions;

use PHPUnit\Framework\Attributes\{CoversClass, Test};
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Webify\Base\Domain\Event\DomainEventPublisherInterface;
use Webify\Base\Infrastructure\Container\Definitions\EventDefinitions;
use Webify\Base\Infrastructure\Contract\DefinitionsInterface;

/**
 * EventDefinitionsTest tests the eventing definitions group.
 *
 * @internal
 */
#[CoversClass(EventDefinitions::class)]
final class EventDefinitionsTest extends TestCase
{
	/**
	 * Tests that the group implements the definitions contract.
	 */
	#[Test]
	public function testImplementsDefinitionsInterface(): void
	{
		self::assertInstanceOf(DefinitionsInterface::class, new EventDefinitions());
	}

	/**
	 * Tests that the dispatcher and domain event publisher are wired.
	 */
	#[Test]
	public function testGetDefinitionsWiresEventStack(): void
	{
		$definitions = EventDefinitions::getDefinitions();

		self::assertArrayHasKey(EventDispatcherInterface::class, $definitions);
		self::assertArrayHasKey(DomainEventPublisherInterface::class, $definitions);
	}
}
