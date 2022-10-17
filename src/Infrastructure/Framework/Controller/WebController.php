<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Controller;

use OneCMS\Base\Infrastructure\Service\Application\WebApplicationServiceInterface;
use yii\base\Module;
use yii\web\Controller;

/**
 * WebController class is the parent class for web request controller classes.
 */
class WebController extends Controller
{
	/**
	 * The constructor.
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
