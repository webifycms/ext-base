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

namespace Webify\Base\Infrastructure\Component\Theme;

use Webify\Base\Domain\Service\Theme\ThemeServiceInterface;
use yii\base\Theme as BaseTheme;

use function Webify\Base\Infrastructure\set_alias;

/**
 * Theme abstraction class based on framework theme class.
 */
abstract class ThemeComponent extends BaseTheme implements ThemeServiceInterface
{
	/**
	 * Initializes the theme component by setting the current theme path as `@Theme` alias.
	 */
	public function init(): void
	{
		set_alias('@Theme', $this->getBasePath());
		parent::init();
	}

	/**
	 * @param array<string, string> $pathAndMap
	 */
	public function addToPathMap(array $pathAndMap): void
	{
		$this->pathMap = array_merge($this->pathMap ?? [], $pathAndMap);
	}
}
