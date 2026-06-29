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

namespace Webify\Base\Test\Unit\Domain\ValueObject\Example;

use InvalidArgumentException;
use Webify\Base\Domain\ValueObject\Slug;

/**
 * ExampleSlug is a concrete implementation of the Slug value object for testing purposes.
 */
final readonly class ExampleSlug extends Slug
{
	/**
	 * {@inheritDoc}
	 */
	protected function throwException(string $value): never
	{
		throw new InvalidArgumentException('Invalid example slug: ' . $value);
	}
}
