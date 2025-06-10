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

namespace Webify\Base\Infrastructure\Component\View;

use yii\web\View;

/**
 * Represents a specialised view component that interacts with the ViewInjectorRegistryService
 * to dynamically inject parameters into the rendering process.
 */
final class WebViewComponent extends View {}
