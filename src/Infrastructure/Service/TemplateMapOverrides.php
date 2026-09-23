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

use Psr\Cache\CacheItemPoolInterface;
use Webify\Base\Domain\ValueObject\TemplatePath;
use Webify\Base\Infrastructure\Contract\{TemplateMapOverridesInterface, ThemeResolverInterface};

use function array_key_exists;
use function hash;
use function sprintf;

/**
 * Concrete implementation of the `TemplateMapOverridesInterface`.
 *
 * The generated override lists are cached per extension id. Because the cache
 * pool is shared and long lived (e.g. the filesystem adapter with no expiry),
 * the cache key is bound to the inputs that determine the result — the app
 * override for the extension and the current theme template maps. A
 * configuration change therefore produces a fresh cache entry instead of
 * serving stale candidates from a previous deployment.
 */
final readonly class TemplateMapOverrides implements TemplateMapOverridesInterface
{
	/**
	 * Cache namespace prefix used to isolate the override-map entries.
	 */
	private const string CACHE_NAMESPACE = 'theme.override-map.';

	/**
	 * Hashing algorithm used to fingerprint the override inputs into the key.
	 */
	private const string SIGNATURE_ALGO = 'xxh64';

	/**
	 * The constructor.
	 *
	 * @param array<string, string> $appOverrides
	 */
	public function __construct(
		private array $appOverrides,
		private ThemeResolverInterface $resolver,
		private CacheItemPoolInterface $cacheItemPool
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function forExtension(string $extensionId): array
	{
		$themeMaps = $this->resolver->themeTemplateMaps();

		$item = $this->cacheItemPool->getItem($this->cacheKey($extensionId, $themeMaps));

		if ($item->isHit()) {
			return $item->get();
		}

		$pathMap = [];

		if (array_key_exists($extensionId, $this->appOverrides)) {
			$pathMap[] = TemplatePath::fromString($this->appOverrides[$extensionId]);
		}

		foreach ($themeMaps as $themeMap) {
			if ([] !== $themeMap && array_key_exists($extensionId, $themeMap)) {
				$pathMap[] = $themeMap[$extensionId];
			}
		}

		$item->set($pathMap);
		$this->cacheItemPool->save($item);

		// When there are no overrides, an empty list is returned so the caller
		// falls back to the extension's own default template path.
		return $pathMap;
	}

	/**
	 * Builds a content-addressed cache key for the given extension.
	 *
	 * The key binds the extension id, this extension's app override and the
	 * current theme template maps, so a change in any of these inputs produces
	 * a different key and therefore a fresh (non-stale) cache value.
	 *
	 * @param array<int, array<string, TemplatePath>> $themeMaps
	 */
	private function cacheKey(string $extensionId, array $themeMaps): string
	{
		$appSignature = hash(self::SIGNATURE_ALGO, $this->appOverrides[$extensionId] ?? '');

		$themeSignature = '';
		foreach ($themeMaps as $themeMap) {
			foreach ($themeMap as $mapExtensionId => $path) {
				$themeSignature .= $mapExtensionId . '=' . $path->toNative() . "\n";
			}
		}

		return sprintf(
			'%s%s.%s.%s',
			self::CACHE_NAMESPACE,
			$extensionId,
			$appSignature,
			hash(self::SIGNATURE_ALGO, $themeSignature)
		);
	}
}
