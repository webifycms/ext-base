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

namespace Webify\Base\Test\Unit\Infrastructure\Provider;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Webify\Base\Infrastructure\Contract\ServiceProviderInterface;
use Webify\Base\Infrastructure\Provider\BaseServiceProvider;

/**
 * BaseServiceProviderTest tests the functionality of the BaseServiceProvider class.
 *
 * @internal
 */
#[CoversClass(BaseServiceProvider::class)]
#[CoversMethod(BaseServiceProvider::class, 'getDefinitions')]
final class BaseServiceProviderTest extends TestCase
{
	/**
	 * Test that the provider implements ServiceProviderInterface.
	 */
	#[Test]
	public function testImplementsInterfaces(): void
	{
		$provider = new BaseServiceProvider();

		self::assertInstanceOf(ServiceProviderInterface::class, $provider);
	}

	/**
	 * Test that getDefinitions returns an array of definitions.
	 */
	#[Test]
	public function testGetDefinitionsReturnsArray(): void
	{
		$provider = new BaseServiceProvider();

		self::assertIsArray($provider->getDefinitions());
	}
}
