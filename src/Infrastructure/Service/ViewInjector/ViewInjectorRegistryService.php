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

namespace Webify\Base\Infrastructure\Service\ViewInjector;

/**
 * This class serves as a registry for managing view injectors, providing
 * mechanisms to store, retrieve, and handle injector instances. It is
 * designed to facilitate the integration and execution of specific
 * view-related functionality across different components.
 */
final class ViewInjectorRegistryService
{
	/**
	 * @var ViewInjectorServiceInterface[]
	 */
	private array $injectors = [];

	/**
	 * Registers a view injector service.
	 */
	public function register(ViewInjectorServiceInterface $injector): void
	{
		if (in_array($injector, $this->injectors, true)) {
			return;
		}

		$this->injectors[] = $injector;
	}

	/**
	 * Collects data from injectors by sorting it using predefined sorters.
	 *
	 * @return array<string, mixed>
	 */
	public function collectAll(): array
	{
		$data = [];

		foreach ($this->injectors as $injector) {
			$injector->collect($data);
		}

		return $data;
	}
}
