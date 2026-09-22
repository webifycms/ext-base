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

namespace Webify\Base\Infrastructure\Service;

use Webify\Base\Domain\ValueObject\Hash;
use Webify\Base\Infrastructure\Contract\{AssetsProviderInterface, ThemeInterface};

/**
 * The default assets provider for a generic theme.
 *
 * It derives a stable hash from the theme identity and serves the theme's `dist`
 * directory. Any theme that ships pre-built assets under `dist` can use this
 * provider; themes with bespoke publishing needs can supply their own.
 */
final readonly class ThemeAssetsProvider implements AssetsProviderInterface
{
	/**
	 * The hash object.
	 */
	private Hash $hash;

	/**
	 * The constructor.
	 */
	public function __construct(
		private ThemeInterface $theme
	) {
		$this->hash = Hash::generate(
			'crc32',
			$this->theme->getId() . $this->theme->getName() . $this->theme->getVersion()
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHash(): string
	{
		return $this->hash->toNative();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDirectory(): string
	{
		return $this->theme->getRootPath() . '/dist';
	}
}
