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

namespace Webify\Base\Contract\Exception;

use Webify\Base\Contract\Translation\ExceptionTranslation;

/**
 * Contract for exceptions that can be translated.
 */
interface TranslatableExceptionInterface
{
	/**
	 * The translation object DTO for this exception.
	 */
	public ExceptionTranslation $translation {
		get;
	}
}
