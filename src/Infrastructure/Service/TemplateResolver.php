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

use Webify\Base\Infrastructure\Contract\{ExtensionInterface, TemplateMapOverridesInterface, TemplateResolverInterface};

/**
 * The service implementing the template resolver.
 *
 * Try to resolve the template for the given extension using the templates map.
 */
final readonly class TemplateResolver implements TemplateResolverInterface
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private TemplateMapOverridesInterface $templateMap
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function resolve(ExtensionInterface $extension, string $template): array
	{
		$candidates = $this->templateMap->forExtension($extension->getId());
		$trimmed    = ltrim($template, '/');
		$paths      = [];

		foreach ($candidates as $path) {
			$paths[] = $path->toNative() . '/' . $trimmed;
		}

		// The extension's own default template path always participates as the
		// final fallback, whether or not any override candidates exist.
		$paths[] = $extension->getTemplatePath()->toNative() . '/' . $trimmed;

		return $paths;
	}
}
