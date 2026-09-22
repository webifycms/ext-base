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
use Webify\Base\Infrastructure\Container\Definitions\ServiceDefinitions;
use Webify\Base\Infrastructure\Contract\{AssetsPublisherInterface, DefinitionsInterface};

/**
 * ServiceDefinitionsTest tests the theme and asset publishing definitions group.
 *
 * @internal
 */
#[CoversClass(ServiceDefinitions::class)]
final class ServiceDefinitionsTest extends TestCase
{
	/**
	 * Tests that the group implements the definitions contract.
	 */
	#[Test]
	public function testImplementsDefinitionsInterface(): void
	{
		self::assertInstanceOf(DefinitionsInterface::class, new ServiceDefinitions());
	}

	/**
	 * Tests that the asset publisher is wired.
	 */
	#[Test]
	public function testGetDefinitionsWiresAssetPublisher(): void
	{
		$definitions = ServiceDefinitions::getDefinitions();

		self::assertArrayHasKey(AssetsPublisherInterface::class, $definitions);
	}
}
