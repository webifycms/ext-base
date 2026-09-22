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
 * Contract for view.
 */
interface ViewInterface
{
	/**
	 * Can set and get the layout.
	 * This is useful to change the layout per view.
	 */
	public string $layout { get; set; }

	/**
	 * Can set and get the extension, if the view service is called within the extension context this can be useful.
	 */
	public ?ExtensionInterface $extension { get; set; }

	/**
	 * Renders a template.
	 *
	 * @param string               $template the template path or name, file extension not required
	 * @param array<string, mixed> $data     the data should pass to the templates
	 * @param null|string          $layout   optional layout, if `null` default will be used
	 */
	public function render(string $template, array $data = [], ?string $layout = null): string;

	/**
	 * Helper method to resolve the Url.
	 *
	 * @param string            $name       the name to get the route
	 * @param array<int|string> $parameters query parametrs
	 * @param UrlType           $type       the url type, default relative
	 */
	public function url(string $name, array $parameters = [], UrlType $type = UrlType::Relative): string;

	/**
	 * Helper method to resolve asset file Url.
	 *
	 * @param string $file asset file or file path
	 */
	public function asset(string $file): string;

	/**
	 * Helper method to resolve asset entrypoint Url.
	 */
	public function assetEntrypoint(string $entry): string;

	/**
	 * Helper method to resolve a plain path-based URL (e.g. a static asset or a
	 * direct path), in contrast to {@link self::url()} which resolves a named route.
	 */
	public function path(string $path, UrlType $type = UrlType::Relative): string;
}
