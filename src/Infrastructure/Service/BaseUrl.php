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

use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\BaseUrlInterface;
use Webify\Base\Infrastructure\Exception\BaseUrlConfigurationException;

use function parse_url;
use function sprintf;
use function str_contains;
use function str_starts_with;
use function trim;

/**
 * The implementation for the base URL.
 *
 * Parses a loosely specified base URL configuration value into its structured
 * parts, so scheme/host/port/path ambiguity is resolved once, at boot,
 * rather than reparsed on every URL generation call.
 *
 * Accepts any of:
 *  ''                           -> not configured
 *  'example.com'                -> host only, no scheme
 *  'example.com:8080'           -> host with port
 *  '//example.com'              -> explicit protocol-relative
 *  'https://example.com'        -> full, with scheme
 *  'https://example.com/blog'   -> full, with a subdirectory prefix
 */
final readonly class BaseUrl implements BaseUrlInterface
{
	/**
	 * The default scheme.
	 */
	private const string DEFAULT_SCHEME = 'https';

	/**
	 * Private constructor enforces the use of the factory method.
	 */
	private function __construct(
		private ?string $scheme,
		private string $host,
		private ?int $port,
		private string $path
	) {}

	/**
	 * Factory method to initialize this from the config.
	 *
	 * @throws BaseUrlConfigurationException if the configurred base URL is not valid
	 */
	public static function fromConfig(ConfigInterface $config): self
	{
		$baseUrl = (string) $config->get('baseUrl', '');
		$scheme  = (string) $config->get('defaultUrlScheme', self::DEFAULT_SCHEME);
		// parse_url() only extracts the host correctly when a scheme or a
		// leading "//" is present, so normalize bare host values first.
		$normalized = trim($baseUrl);

		if ('' === $normalized) {
			return new self(null, '', null, '');
		}

		if (!str_contains($normalized, '://') && !str_starts_with($normalized, '//')) {
			$normalized = '//' . $normalized;
		}

		$parts = parse_url($normalized);

		if (false === $parts || !isset($parts['host']) || '' === $parts['host']) {
			throw BaseUrlConfigurationException::forInvalid($baseUrl);
		}

		return new self(
			$parts['scheme'] ?? trim($scheme),
			$parts['host'],
			$parts['port'] ?? null,
			rtrim($parts['path'] ?? '', '/')
		);
	}

	/**
	 * Get the native string value of the base URL.
	 *
	 * @throws BaseUrlConfigurationException if base URL is not configurred
	 */
	public function toNative(?string $scheme = null): string
	{
		if ('' === $this->host) {
			throw BaseUrlConfigurationException::notConfigured();
		}

		$url             = null !== $this->port ? sprintf('%s:%s', $this->host, $this->port) : $this->host;
		$affectiveScheme = $scheme ?? $this->scheme;

		// An empty (or null) effective scheme yields a protocol-relative URL,
		// while a concrete base URL must not end with a trailing slash so that
		// consumers can safely append a "/path" segment.
		return '' === (string) $affectiveScheme
			? sprintf('//%s%s', $url, $this->path)
			: sprintf('%s://%s%s', $affectiveScheme, $url, $this->path);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasScheme(): bool
	{
		return null !== $this->scheme;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getScheme(): ?string
	{
		return $this->scheme;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHost(): string
	{
		return $this->host;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPathPrefix(): string
	{
		return $this->path;
	}
}
