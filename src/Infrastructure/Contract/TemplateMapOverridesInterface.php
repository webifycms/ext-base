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

use Webify\Base\Domain\ValueObject\TemplatePath;

/**
 * Contract for template map overrides.
 */
interface TemplateMapOverridesInterface
{
	/**
	 * Override template map for the extension by its ID.
	 *
	 * @return TemplatePath[] override candidates for the given extension id,
	 *                        ordered highest priority first. Does NOT include
	 *                        the extension's own default — callers append that
	 *                        themselves as the final fallback.
	 */
	public function forExtension(string $extensionId): array;
}
