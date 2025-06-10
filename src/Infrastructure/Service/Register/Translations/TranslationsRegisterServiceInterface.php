<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service\Register\Translations;

/**
 * Interface for register translations.
 */
interface TranslationsRegisterServiceInterface
{
	/**
	 * Returns the translation category.
	 */
	public function getCategory(): string;

	/**
	 * Returns the translation configurations.
	 *
	 * @return array{
	 *     class: string,
	 *     sourceLanguage: string,
	 *     basePath: string
	 * }
	 */
	public function getConfigurations(): array;
}
