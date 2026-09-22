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

namespace Webify\Base\Infrastructure\Helper;

use Webify\Base\Contract\EscaperInterface;

/**
 * The default HTML escaper.
 *
 * Converts special characters to HTML entities with `htmlspecialchars`,
 * covering both single and double quotes (ENT_QUOTES) in UTF-8.
 */
final readonly class Escaper implements EscaperInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function escape(string $string): string
	{
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}
}
