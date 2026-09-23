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

namespace Webify\Base\Domain\ValueObject;

use Webify\Base\Domain\Exception\InvalidThemeSlugException;

/**
 * Value object represents theme slug.
 */
final readonly class ThemeSlug extends Slug
{
	/**
	 * {@inheritDoc}
	 */
	protected function throwException(string $value): never
	{
		throw InvalidThemeSlugException::forInvalid($value);
	}
}
