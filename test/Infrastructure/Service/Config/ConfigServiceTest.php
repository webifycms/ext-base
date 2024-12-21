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

namespace Webify\Base\Test\Infrastructure\Service\Config;

use PHPUnit\Framework\TestCase;
use Webify\Base\Infrastructure\Service\Config\ConfigService;

/**
 * @coversDefaultClass \Webify\Base\Infrastructure\Service\Config\ConfigService
 *
 * @internal
 */
final class ConfigServiceTest extends TestCase
{
	private ConfigService $config;

	protected function setUp(): void
	{
		$this->config = new ConfigService([
			'id'   => 'web',
			'name' => 'Application',
		]);
	}

	/**
	 * @covers ::getConfig
	 * @covers ::setConfig
	 */
	public function testCanAddConfigurations(): void
	{
		$this->config->setConfig('string', '')
			->setConfig('array', [])
			->setConfig('callable', fn () => true)
			->setConfig('boolean', true)
		;

		$config = $this->config->getConfig();

		$this->assertArrayHasKey('string', $config);
		$this->assertArrayHasKey('array', $config);
		$this->assertArrayHasKey('callable', $config);
		$this->assertArrayHasKey('boolean', $config);
	}

	/**
	 * @covers ::getConfig
	 */
	public function testCanReturnDefaultValueIfConfigNotFound(): void
	{
		$this->assertSame('foo', $this->config->getConfig('bar', 'foo'));
	}
}
