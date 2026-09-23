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

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Infrastructure\Contract\{AssetsProviderInterface, AssetsPublisherInterface};
use Webify\Base\Infrastructure\Exception\AssetPublishException;

use function file_get_contents;
use function hash;
use function implode;
use function is_dir;
use function is_file;
use function rtrim;
use function sort;

/**
 * Asset publisher service.
 *
 * Publishes the assets declared by an {@see AssetsProviderInterface} into the
 * public webroot under `public/assets/<hash>/`. The provider supplies its own
 * deterministic hash, making republishing the same version a no-op.
 *
 * To never serve stale or partial assets, the source directory is fingerprinted
 * on every publish call. An existing hashed target directory is only trusted
 * when it carries a completion marker matching the current fingerprint; a
 * missing, partial or stale target is reconciled by mirroring the source on top
 * of it (pruning files that no longer exist in the source).
 */
final class AssetsPublisher implements AssetsPublisherInterface
{
	/**
	 * The assets directory name inside the public directory.
	 */
	private const string ASSETS_DIR = 'assets';

	/**
	 * Name of the marker file written after a full mirror completed.
	 */
	private const string COMPLETION_MARKER = '.webify-assets-completed';

	/**
	 * Hashing algorithm used to fingerprint the source directory contents.
	 */
	private const string DIGEST_ALGO = 'sha256';

	/**
	 * The set of provider hashes that have been published during this request.
	 *
	 * Used to avoid redundant copy operations when {@see publish()} is called
	 * multiple times for the same provider.
	 *
	 * @var array<string, true>
	 */
	private array $written = [];

	/**
	 * The constructor.
	 */
	public function __construct(
		private readonly ConfigInterface $config,
		private readonly Filesystem $filesystem
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function publish(AssetsProviderInterface $provider): void
	{
		$key = $provider->getHash();

		if (isset($this->written[$key])) {
			return;
		}

		$sourcePath = rtrim($provider->getDirectory(), '/');

		if (!is_dir($sourcePath)) {
			throw AssetPublishException::sourceNotFound($sourcePath);
		}

		$targetPath = $this->resolveTargetPath($key);
		$digest     = $this->digest($sourcePath);

		if ($this->isUpToDate($targetPath, $digest)) {
			$this->written[$key] = true;

			return;
		}

		try {
			$this->filesystem->mkdir($targetPath);
		} catch (IOException $exception) {
			throw AssetPublishException::targetNotWritable($targetPath, $exception);
		}

		try {
			// `delete` prunes files that no longer exist in the source and
			// `override` refreshes existing ones, so a stale or partial target
			// is fully reconciled with the source.
			$this->filesystem->mirror(
				$sourcePath,
				$targetPath,
				null,
				['delete' => true, 'override' => true]
			);
		} catch (IOException $exception) {
			throw AssetPublishException::copyFailed($sourcePath, $targetPath, $exception);
		}

		try {
			$this->filesystem->dumpFile(
				$targetPath . DIRECTORY_SEPARATOR . self::COMPLETION_MARKER,
				$digest
			);
		} catch (IOException $exception) {
			throw AssetPublishException::targetNotWritable(
				$targetPath . DIRECTORY_SEPARATOR . self::COMPLETION_MARKER,
				$exception
			);
		}

		$this->written[$key] = true;
	}

	/**
	 * Returns the absolute filesystem path for a published provider directory.
	 */
	private function resolveTargetPath(string $hash): string
	{
		return $this->config->publicPath . DIRECTORY_SEPARATOR . self::ASSETS_DIR . DIRECTORY_SEPARATOR . $hash;
	}

	/**
	 * Checks whether the target already holds a full mirror of the given source digest.
	 *
	 * The completion marker guarantees the target was fully mirrored in an
	 * earlier publish; without it, a pre-existing directory could be a partial
	 * mirror from an interrupted deploy and is never trusted.
	 */
	private function isUpToDate(string $targetPath, string $digest): bool
	{
		if (!is_dir($targetPath)) {
			return false;
		}

		$markerPath = $targetPath . DIRECTORY_SEPARATOR . self::COMPLETION_MARKER;

		if (!is_file($markerPath)) {
			return false;
		}

		return (string) file_get_contents($markerPath) === $digest;
	}

	/**
	 * Builds a deterministic content digest of every file under the source directory.
	 *
	 * Relative paths are included and the entries sorted, so renames, removals,
	 * additions and content changes all produce a different digest.
	 */
	private function digest(string $sourcePath): string
	{
		$files = [];

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($sourcePath, FilesystemIterator::SKIP_DOTS)
		);

		foreach ($iterator as $file) {
			/** @var SplFileInfo $file */
			if ($file->isFile()) {
				$files[] = $iterator->getSubPathname() . ':' . (string) file_get_contents($file->getPathname());
			}
		}

		sort($files);

		return hash(self::DIGEST_ALGO, implode('|', $files));
	}
}
