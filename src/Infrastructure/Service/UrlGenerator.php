<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service;

use League\Route\Router;
use Webify\Base\Domain\ValueObject\ResourcePath;
use Webify\Base\Infrastructure\Contract\{BaseUrlInterface, UrlGeneratorInterface, UrlType};

/**
 * Implementation for UrlGenerator.
 */
final readonly class UrlGenerator implements UrlGeneratorInterface
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private Router $router,
		private BaseUrlInterface $baseUrl,
		private string $defaultScheme = 'https'
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function route(string $name, array $parameters = [], UrlType $type = UrlType::Relative): string
	{
		return $this->build(
			$this->normalizePath($this->router->getNamedRoute($name)->getPath($parameters)),
			$type
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function to(ResourcePath $path, UrlType $type = UrlType::Relative): string
	{
		return $this->build($this->normalizePath('/' . $path->toNative()), $type);
	}

	/**
	 * {@inheritDoc}
	 */
	public function path(string $path, UrlType $type = UrlType::Relative): string
	{
		return $this->build($this->normalizePath($path), $type);
	}

	/**
	 * Build thr URL for the given path and type.
	 */
	private function build(string $path, UrlType $type): string
	{
		return match ($type) {
			UrlType::Relative    => $path,
			UrlType::Absolute    => $this->baseUrl
				->toNative($this->baseUrl->hasScheme() ? null : $this->defaultScheme) . $path,
			// A network-path URL is protocol-relative, so the scheme must be
			// dropped regardless of the configured (or default) scheme.
			UrlType::NetworkPath => $this->baseUrl
				->toNative('') . $path
		};
	}

	/**
	 * Normalize the given path.
	 */
	private function normalizePath(string $path): string
	{
		return '/' . ltrim($path, '/');
	}
}
