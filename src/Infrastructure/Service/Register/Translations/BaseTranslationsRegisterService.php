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

use yii\i18n\PhpMessageSource;

/**
 * The service class to register translations for the Base extension.
 */
final class BaseTranslationsRegisterService extends TranslationsRegisterService
{
	public function getCategory(): string
	{
		return 'base';
	}

	/**
	 * @return array{
	 *     class: string,
	 *     sourceLanguage: string,
	 *     basePath: string
	 * }
	 */
	public function getConfigurations(): array
	{
		return [
			'class'          => PhpMessageSource::class,
			'sourceLanguage' => 'en-US',
			'basePath'       => '@Base/resources/translations',
		];
	}
}
