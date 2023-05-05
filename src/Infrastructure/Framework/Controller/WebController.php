<?php

declare(strict_types=1);

namespace Webify\Base\Infrastructure\Framework\Controller;

use Webify\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\base\Module;
use yii\web\Controller;

/**
 * WebController class is the parent class for web request controller classes.
 */
class WebController extends Controller
{
	/**
	 * The constructor.
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
