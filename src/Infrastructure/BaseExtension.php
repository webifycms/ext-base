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

namespace Webify\Base\Infrastructure;

use Webify\Base\Domain\BaseExtensionInterface;
use Webify\Base\Domain\Service\Application\ApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use Webify\Base\Infrastructure\Service\Register\Dependencies\BaseDependenciesRegisterService;
use Webify\Base\Infrastructure\Service\Register\Translations\BaseTranslationsRegisterService;

/**
 * Represents the base implementation of the extension.
 * This class provides core functionality such as retrieving the extension ID, version,
 * template path, and asset path.
 */
final class BaseExtension implements BaseExtensionInterface
{
	/**
	 * The constructor.
	 */
	public function __construct()
	{
		set_alias('@Base', '@Extensions/ext-base');
	}

	public function initialize(ApplicationServiceInterface $appService): void {}

	public function getId(): string
	{
		return strtolower(self::NAME);
	}

	public function getInterface(): string
	{
		return BaseExtensionInterface::class;
	}

	public function getVersion(): string
	{
		return self::VERSION;
	}

	public function getTemplatesPath(): ?string
	{
		return null;
	}

	public function getAssetsPath(): ?string
	{
		return null;
	}

	public function getAssetsPublishedUrl(): ?string
	{
		return null;
	}

	/**
	 * @return array<string>
	 */
	public function getRegisterServices(): array
	{
		return [
			BaseDependenciesRegisterService::class,
			BaseTranslationsRegisterService::class,
		];
	}

	public static function getInstance(): BaseExtensionInterface
	{
		// @phpstan-ignore-next-line
		return di()
			->getContainer()
			->get(WebApplicationServiceInterface::class)
			->getExtension(BaseExtensionInterface::class)
		;
	}
}
