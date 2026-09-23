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

namespace Webify\Base\Contract;

/**
 * EscaperInterface escapes untrusted strings for safe HTML output.
 *
 * It is a common, layer-agnostic contract — not owned by theming — so any
 * renderer, helper, or extension can rely on it without touching the theme
 * stack.
 */
interface EscaperInterface
{
	/**
	 * Escapes a string for safe embedding into HTML.
	 *
	 * @param string $string the raw string to escape
	 */
	public function escape(string $string): string;
}
