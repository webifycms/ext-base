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

namespace Webify\Base\Test\Unit\Infrastructure\Container\Example;

use Webify\Base\Infrastructure\Contract\ServiceProviderInterface;

/**
 * ExampleExtensionServiceProvider class for testing purposes.
 *
 * @internal
 */
final class ExampleExtensionServiceProvider implements ServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getDefinitions(): array
	{
		return ['extension.key' => 'extension.value'];
	}
}
