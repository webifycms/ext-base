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

namespace Webify\Base\Infrastructure\Component\Application;

use Yii;
use yii\web\Application;

/**
 * This is an extended version of the web application to add support for multiple namespaces.
 */
final class WebApplicationComponent extends Application
{
	/**
	 * The namespaces that controller classes are located in. This namespace will be used to load controller
	 * classes by prepending it to the controller class name.
	 *
	 * @var array<string>
	 */
	public array $controllerNamespaces = [];

	/**
	 * Add support to the multiple namespaces.
	 * {@inheritDoc}
	 *
	 * @return array<int, mixed>
	 */
	public function createController($route): array|bool
	{
		$route = '' === $route ? $this->defaultRoute : $route;
		// double slashes or leading/ending slashes may cause substr problem
		$route = trim($route, '/');

		if (str_contains($route, '//')) {
			return false;
		}

		if (str_contains($route, '/')) {
			[$id, $route] = explode('/', $route, 2);
		} else {
			$id    = $route;
			$route = '';
		}

		// tries controller namespaces first
		if (!empty($this->controllerNamespaces)) {
			foreach ($this->controllerNamespaces as $namespace) {
				$className = $namespace . '\\' . ucfirst($id) . 'Controller';

				if (class_exists($className)) {
					$controller = Yii::createObject($className, [$id, $this]);

					return [$controller, $route];
				}
			}
		}

		// check in the controller map
		if (isset($this->controllerMap[$id])) {
			$controller = Yii::createObject($this->controllerMap[$id], [$id, $this]);

			return [$controller, $route];
		}

		// check under modules
		$module = $this->getModule($id);

		if (null !== $module) {
			return $module->createController($route);
		}

		// check the current module
		if (false !== ($pos = strrpos($route, '/'))) {
			$id .= '/' . substr($route, 0, $pos);
			$route = substr($route, $pos + 1);
		}

		$controller = $this->createControllerByID($id);

		if (null === $controller && '' !== $route) {
			$controller = $this->createControllerByID($id . '/' . $route);
			$route      = '';
		}

		return null === $controller ? false : [$controller, $route];
	}
}
