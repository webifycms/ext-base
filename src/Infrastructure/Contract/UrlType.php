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
 * The type of URL that can be used to generate.
 */
enum UrlType
{
	/**
	 * "/blog/my-post" — no host, always available regardless of config.
	 */
	case Relative;

	/**
	 * "https://example.com/blog/my-post" — fully qualified url.
	 */
	case Absolute;

	/**
	 * "//example.com/blog/my-post" — protocol-relative, browser inherits the current scheme.
	 */
	case NetworkPath;
}
