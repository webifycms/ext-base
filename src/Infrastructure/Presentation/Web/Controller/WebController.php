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

namespace Webify\Base\Infrastructure\Presentation\Web\Controller;

use Webify\Base\Infrastructure\Component\Theme\ThemeComponent;
use yii\web\Controller;

/**
 * WebController class is the parent class for web request controller classes.
 */
abstract class WebController extends Controller
{
	/**
	 * Add theme support for the view files.
	 *
	 * @param array<string> $pathMap
	 */
	final public function addThemeSupport(array $pathMap): void
	{
		$theme = $this->view->theme;

		if ($theme instanceof ThemeComponent) {
			$theme->addToPathMap($pathMap);
		}
	}
}
