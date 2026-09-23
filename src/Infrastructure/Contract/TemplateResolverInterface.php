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
 * Contract for template resolver.
 */
interface TemplateResolverInterface
{
	/**
	 * Resolved the template.
	 *
	 * @return array<string>
	 */
	public function resolve(ExtensionInterface $extension, string $template): array;
}
