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
 * The asset pipeline contract.
 *
 * Given the set of asset entry points registered by extensions, the app, and the theme itself,
 * produce servable output.
 */
interface AssetsPipelineInterface
{
	/**
	 * Resolves the full HTML tags (script + associated CSS links) needed to
	 * load a JS/TS entry point, since a single entry can pull in CSS and
	 * dependency chunks that must be emitted alongside it.
	 */
	public function entrypoint(string $entry): string;

	/**
	 * Resolves the URL for a single static asset (image, font, etc.).
	 *
	 * @param string $entry the asset path
	 */
	public function url(string $entry): string;
}
