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

namespace Webify\Base\Infrastructure\Contract;

/**
 * The contract for base URL.
 */
interface BaseUrlInterface
{
	/**
	 * Get the native string value of the base URL.
	 */
	public function toNative(?string $scheme = null): string;

	/**
	 * Check weather has scheme.
	 */
	public function hasScheme(): bool;

	/**
	 * Returns the scheme.
	 */
	public function getScheme(): ?string;

	/**
	 * Returns the host.
	 */
	public function getHost(): string;

	/**
	 * Returns the path prefix.
	 */
	public function getPathPrefix(): string;
}
