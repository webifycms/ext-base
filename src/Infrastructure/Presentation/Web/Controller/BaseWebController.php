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

use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\base\Module;
use yii\web\Controller;

/**
 * WebController class is the parent class for web request controller classes.
 */
abstract class BaseWebController extends Controller
{
	/**
	 * The object constructor.
	 *
	 * @param array<string,mixed> $config
	 */
	public function __construct(
		string $id,
		Module $module,
		protected readonly WebApplicationServiceInterface $appService,
		array $config = []
	) {
		parent::__construct($id, $module, $config);
	}
}
