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

namespace Webify\Base\Test\Unit\Infrastructure\Provider;

use PHPUnit\Framework\Attributes\{CoversClass, CoversMethod, Test};
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Webify\Base\Domain\Event\DomainEventPublisherInterface;
use Webify\Base\Infrastructure\Contract\{
	AssetsPublisherInterface,
	ServiceProviderInterface,
	TemplateMapOverridesInterface
};
use Webify\Base\Infrastructure\Provider\BaseService;

/**
 * BaseServiceTest tests the functionality of the BaseService class.
 *
 * @internal
 */
#[CoversClass(BaseService::class)]
#[CoversMethod(BaseService::class, 'getDefinitions')]
final class BaseServiceTest extends TestCase
{
	/**
	 * Test that the provider implements ServiceProviderInterface.
	 */
	#[Test]
	public function testImplementsInterfaces(): void
	{
		$provider = new BaseService();

		self::assertInstanceOf(ServiceProviderInterface::class, $provider);
	}

	/**
	 * Test that getDefinitions returns an array of definitions.
	 */
	#[Test]
	public function testGetDefinitionsReturnsArray(): void
	{
		$provider = new BaseService();

		self::assertIsArray($provider->getDefinitions());
	}

	/**
	 * Test that getDefinitions merges every definition group.
	 */
	#[Test]
	public function testGetDefinitionsMergesEveryGroup(): void
	{
		$definitions = new BaseService()->getDefinitions();

		self::assertArrayHasKey(RequestFactoryInterface::class, $definitions);
		self::assertArrayHasKey(ConsoleApplication::class, $definitions);
		self::assertArrayHasKey(DomainEventPublisherInterface::class, $definitions);
		self::assertArrayHasKey(LoggerInterface::class, $definitions);
		self::assertArrayHasKey(AssetsPublisherInterface::class, $definitions);
		self::assertArrayHasKey(TemplateMapOverridesInterface::class, $definitions);
	}
}
