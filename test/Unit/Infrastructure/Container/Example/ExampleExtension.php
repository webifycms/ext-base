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

namespace Webify\Base\Test\Unit\Infrastructure\Container\Example;

use Webify\Base\Infrastructure\Contract\{ExtensionInterface, ServiceProviderInterface};

/**
 * Example extension class for testing purposes.
 *
 * @internal
 */
final class ExampleExtension implements ExtensionInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getId(): string
	{
		return 'example';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return 'Example Extension';
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return array<class-string<ServiceProviderInterface>>
	 */
	public function getProviders(): array
	{
		return [ExampleExtensionServiceProvider::class];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getVersion(): string
	{
		return '1.0.0';
	}
}
