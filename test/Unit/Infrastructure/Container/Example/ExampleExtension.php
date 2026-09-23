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

namespace Webify\Base\Test\Unit\Infrastructure\Container\Example;

use Webify\Base\Domain\ValueObject\TemplatePath;
use Webify\Base\Infrastructure\Contract\{AssetsProviderInterface, ExtensionInterface};

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
	 * @return list<class-string<ExampleExtensionServiceProvider>>
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

	/**
	 * {@inheritdoc}
	 */
	public function getSlug(): string
	{
		return 'example-extension';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTemplatePath(): TemplatePath
	{
		return TemplatePath::fromString(__DIR__ . '/../templates');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAssetsProvider(): ?AssetsProviderInterface
	{
		return null;
	}
}
