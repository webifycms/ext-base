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

use Webify\Base\Domain\ValueObject\ResourcePath;

/**
 * The contract for Url generation.
 */
interface UrlGeneratorInterface
{
	/**
	 * Gets the route for the given name.
	 *
	 * @param string            $name       the name to get the route
	 * @param array<int|string> $parameters query parametrs
	 * @param UrlType           $type       the url type, default relative
	 */
	public function route(string $name, array $parameters = [], UrlType $type = UrlType::Relative): string;

	/**
	 * Resolve the URL for the given resource path.
	 */
	public function to(ResourcePath $path, UrlType $type = UrlType::Relative): string;

	/**
	 * Resolve a plain path (e.g. a static asset or a direct URL) against the
	 * base URL, without requiring a route name or a `ResourcePath` value object.
	 *
	 * @param string  $path the path to resolve, typically starting with `/`
	 * @param UrlType $type the URL type, default relative
	 */
	public function path(string $path, UrlType $type = UrlType::Relative): string;
}
