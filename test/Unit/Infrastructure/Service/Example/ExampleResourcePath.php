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

namespace Webify\Base\Test\Unit\Infrastructure\Service\Example;

use InvalidArgumentException;
use Webify\Base\Domain\ValueObject\ResourcePath;

/**
 * ExampleResourcePath is a concrete implementation of the ResourcePath value object for testing purposes.
 */
final readonly class ExampleResourcePath extends ResourcePath
{
	/**
	 * {@inheritDoc}
	 */
	protected function throwException(string $value): never
	{
		throw new InvalidArgumentException('Invalid example resource path: ' . $value);
	}
}
