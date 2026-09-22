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

namespace Webify\Base\Test\Unit\Infrastructure\Service;

use PHPUnit\Framework\Attributes\{CoversClass, Test, UsesClass};
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Webify\Base\Application\Service\ConfigInterface;
use Webify\Base\Domain\ValueObject\Hash;
use Webify\Base\Infrastructure\Contract\{AssetsProviderInterface, AssetsPublisherInterface};
use Webify\Base\Infrastructure\Exception\AssetPublishException;
use Webify\Base\Infrastructure\Service\{AssetsPublisher, Config};

/**
 * AssetPublisherTest tests the functionality of the AssetPublisher service.
 *
 * @internal
 */
#[CoversClass(AssetsPublisher::class)]
#[CoversClass(AssetPublishException::class)]
#[UsesClass(Config::class)]
#[UsesClass(Hash::class)]
#[UsesClass(IOException::class)]
final class AssetsPublisherTest extends TestCase
{
	/**
	 * Temporary directory to isolate the test filesystem activity.
	 */
	private string $tempDir;

	/**
	 * The public path (web root) used as the publishing target.
	 */
	private string $publicPath;

	/**
	 * The test configuration instance.
	 */
	private ConfigInterface $config;

	/**
	 * The asset publisher under test.
	 */
	private AssetsPublisher $publisher;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		$this->tempDir    = sys_get_temp_dir() . '/webify_asset_test_' . uniqid('', true);
		$this->publicPath = $this->tempDir . '/public';

		mkdir($this->publicPath, 0o755, true);

		$this->config    = new Config([
			'basePath'    => $this->tempDir,
			'publicPath'  => $this->publicPath,
			'runtimePath' => $this->tempDir . '/runtime',
			'configPath'  => $this->tempDir . '/config',
		]);
		$this->publisher = new AssetsPublisher($this->config, new Filesystem());
	}

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void
	{
		$this->removeDirectory($this->tempDir);
	}

	/**
	 * Test that the publisher implements the AssetPublisherInterface.
	 */
	#[Test]
	public function testImplementsAssetPublisherInterface(): void
	{
		self::assertInstanceOf(AssetsPublisherInterface::class, $this->publisher);
	}

	/**
	 * Test that publish copies the source files to a hashed directory under the public path.
	 */
	#[Test]
	public function testPublishCopiesFilesToTargetDirectory(): void
	{
		$source = $this->createSourceDirectory([
			'app.js'  => 'console.log("hello");',
			'app.css' => 'body { margin: 0; }',
		]);
		$provider = $this->createProvider('abc123', $source);

		$this->publisher->publish($provider);
		self::assertDirectoryExists($this->publicPath . '/assets/abc123');
		self::assertFileExists($this->publicPath . '/assets/abc123/app.js');
		self::assertFileExists($this->publicPath . '/assets/abc123/app.css');
		self::assertSame(
			'console.log("hello");',
			file_get_contents($this->publicPath . '/assets/abc123/app.js')
		);
	}

	/**
	 * Test that publish preserves the nested directory structure of the source.
	 */
	#[Test]
	public function testPublishCopiesNestedDirectories(): void
	{
		$source = $this->createSourceDirectory([
			'js'  => [
				'main.js' => '// main',
			],
			'css' => [
				'style.css' => '/* style */',
			],
		]);
		$provider = $this->createProvider('abc123', $source);

		$this->publisher->publish($provider);
		self::assertFileExists($this->publicPath . '/assets/abc123/js/main.js');
		self::assertFileExists($this->publicPath . '/assets/abc123/css/style.css');
		self::assertSame(
			'// main',
			file_get_contents($this->publicPath . '/assets/abc123/js/main.js')
		);
	}

	/**
	 * Test that publishing the same provider twice does not fail.
	 */
	#[Test]
	public function testPublishIsIdempotentForSameProvider(): void
	{
		$source = $this->createSourceDirectory([
			'app.js' => 'console.log("hello");',
		]);
		$provider = $this->createProvider('abc123', $source);

		$this->publisher->publish($provider);
		$this->publisher->publish($provider);
		self::assertFileExists($this->publicPath . '/assets/abc123/app.js');
	}

	/**
	 * Test that publish handles an empty source directory.
	 */
	#[Test]
	public function testPublishEmptyDirectory(): void
	{
		$source  = $this->tempDir . '/source_empty';

		mkdir($source, 0o755, true);

		$provider = $this->createProvider('abc123', $source);

		$this->publisher->publish($provider);
		self::assertDirectoryExists($this->publicPath . '/assets/abc123');
	}

	/**
	 * Test that publish throws an exception when the source directory does not exist.
	 */
	#[Test]
	public function testPublishThrowsExceptionForMissingSourceDirectory(): void
	{
		$this->expectException(AssetPublishException::class);

		$provider = $this->createProvider('abc123', '/nonexistent/path');

		$this->publisher->publish($provider);
	}

	/**
	 * Test that different hashes publish to different directories.
	 */
	#[Test]
	public function testDifferentHashesPublishToDifferentDirectories(): void
	{
		$source1   = $this->createSourceDirectory(['app.js' => '// app']);
		$source2   = $this->createSourceDirectory(['ext.js' => '// ext']);
		$provider1 = $this->createProvider('abc123', $source1);
		$provider2 = $this->createProvider('xyz789', $source2);

		$this->publisher->publish($provider1);
		$this->publisher->publish($provider2);
		self::assertFileExists($this->publicPath . '/assets/abc123/app.js');
		self::assertFileExists($this->publicPath . '/assets/xyz789/ext.js');
	}

	/**
	 * Test that publish throws an AssetPublishException when mkdir fails because
	 * the target directory is not writable.
	 */
	#[Test]
	public function testPublishThrowsTargetNotWritableWhenMkdirFails(): void
	{
		$source   = $this->createSourceDirectory(['app.js' => 'console.log("hello");']);
		$provider = $this->createProvider('abc123', $source);

		$publisher = new AssetsPublisher($this->config, new FailingMkdirFilesystem());

		try {
			$publisher->publish($provider);
			self::fail('Expected an AssetPublishException to be thrown when mkdir fails.');
		} catch (AssetPublishException $exception) {
			self::assertMatchesRegularExpression('/not writable/', $exception->getMessage());
			self::assertInstanceOf(IOException::class, $exception->getPrevious());
			self::assertSame('target_not_writable', $exception->translation->key);
		}
	}

	/**
	 * Test that publish throws an AssetPublishException when the mirror operation
	 * fails while copying the asset files.
	 */
	#[Test]
	public function testPublishThrowsCopyFailedWhenMirrorFails(): void
	{
		$source   = $this->createSourceDirectory(['app.js' => 'console.log("hello");']);
		$provider = $this->createProvider('abc123', $source);

		$publisher = new AssetsPublisher($this->config, new FailingMirrorFilesystem());

		try {
			$publisher->publish($provider);
			self::fail('Expected an AssetPublishException to be thrown when mirror fails.');
		} catch (AssetPublishException $exception) {
			self::assertMatchesRegularExpression('/Failed to copy asset/', $exception->getMessage());
			self::assertInstanceOf(IOException::class, $exception->getPrevious());
			self::assertSame('copy_failed', $exception->translation->key);
		}
	}

	/**
	 * Test that an existing, up-to-date target is not mirrored again.
	 *
	 * A fresh publisher instance simulates a new request: the second publish is
	 * a verified no-op, so even a mirror that would always fail must not run.
	 */
	#[Test]
	public function testPublishSkipsUpToDateTarget(): void
	{
		$source   = $this->createSourceDirectory(['app.js' => '// app']);
		$provider = $this->createProvider('abc123', $source);

		$this->publisher->publish($provider);

		$publisher = new AssetsPublisher($this->config, new FailingMirrorFilesystem());
		$publisher->publish($provider);

		self::assertFileExists($this->publicPath . '/assets/abc123/app.js');
		self::assertFileExists($this->publicPath . '/assets/abc123/.webify-assets-completed');
	}

	/**
	 * Test that the completion marker is not trusted when the source content
	 * changed under the same hash, and the target is refreshed.
	 */
	#[Test]
	public function testPublishRefreshesTargetWhenSourceContentChanges(): void
	{
		$source   = $this->createSourceDirectory(['app.js' => '// version 1']);
		$provider = $this->createProvider('abc123', $source);

		$this->publisher->publish($provider);

		// Simulate a rebuilt dist with the same version/hash on a new request.
		file_put_contents($source . '/app.js', '// version 2');

		$publisher = new AssetsPublisher($this->config, new Filesystem());
		$publisher->publish($provider);

		self::assertSame(
			'// version 2',
			file_get_contents($this->publicPath . '/assets/abc123/app.js')
		);
	}

	/**
	 * Test that a partial target from an interrupted earlier publish is repaired.
	 */
	#[Test]
	public function testPublishRepairsPartialTarget(): void
	{
		$source   = $this->createSourceDirectory(['app.js' => '// complete']);
		$provider = $this->createProvider('abc123', $source);

		$target = $this->publicPath . '/assets/abc123';
		mkdir($target, 0o755, true);
		file_put_contents($target . '/app.js', '// partial');

		$this->publisher->publish($provider);

		self::assertSame('// complete', file_get_contents($target . '/app.js'));
		self::assertFileExists($target . '/.webify-assets-completed');
	}

	/**
	 * Test that files removed from the source are pruned from the target.
	 */
	#[Test]
	public function testPublishPrunesRemovedSourceFiles(): void
	{
		$source   = $this->createSourceDirectory([
			'app.js' => '// app',
			'old.js' => '// old',
		]);
		$provider = $this->createProvider('abc123', $source);

		$this->publisher->publish($provider);

		unlink($source . '/old.js');

		$publisher = new AssetsPublisher($this->config, new Filesystem());
		$publisher->publish($provider);

		self::assertFileDoesNotExist($this->publicPath . '/assets/abc123/old.js');
		self::assertFileExists($this->publicPath . '/assets/abc123/app.js');
	}

	/**
	 * Creates an anonymous asset provider for the given hash and source directory.
	 */
	private function createProvider(string $hash, string $sourcePath): AssetsProviderInterface
	{
		return new readonly class(Hash::fromString($hash), $sourcePath) implements AssetsProviderInterface {
			/**
			 * The constructor.
			 */
			public function __construct(
				private Hash $hash,
				private string $assetsDirectory
			) {}

			/**
			 * {@inheritDoc}
			 */
			public function getHash(): string
			{
				return $this->hash->toNative();
			}

			/**
			 * {@inheritDoc}
			 */
			public function getDirectory(): string
			{
				return $this->assetsDirectory;
			}
		};
	}

	/**
	 * Creates a source directory with the given file structure.
	 *
	 * @param array<string, array<string, string>|string> $files a flat map of filename => content
	 *                                                           or nested map of dirname => [filename => content]
	 */
	private function createSourceDirectory(array $files): string
	{
		$source = $this->tempDir . '/source_' . uniqid('', true);

		mkdir($source, 0o755, true);

		foreach ($files as $name => $content) {
			if (is_array($content)) {
				$dir = $source . DIRECTORY_SEPARATOR . $name;

				mkdir($dir, 0o755, true);

				foreach ($content as $fileName => $fileContent) {
					file_put_contents($dir . DIRECTORY_SEPARATOR . $fileName, $fileContent);
				}
			} else {
				file_put_contents($source . DIRECTORY_SEPARATOR . $name, $content);
			}
		}

		return $source;
	}

	/**
	 * Recursively removes a directory and its contents.
	 */
	private function removeDirectory(string $directory): void
	{
		if (!is_dir($directory)) {
			return;
		}

		$items = scandir($directory);

		if (false === $items) {
			return;
		}

		foreach ($items as $item) {
			if ('.' === $item || '..' === $item) {
				continue;
			}

			$path = $directory . DIRECTORY_SEPARATOR . $item;

			if (is_dir($path)) {
				$this->removeDirectory($path);
			} else {
				unlink($path);
			}
		}

		rmdir($directory);
	}
}

/**
 * A Filesystem subclass that always fails on mkdir.
 *
 * @internal
 */
final class FailingMkdirFilesystem extends Filesystem
{
	/**
	 * {@inheritDoc}
	 *
	 * @param iterable<mixed>|string $dirs the directories to create
	 * @param int                    $mode the permission mode
	 */
	public function mkdir(iterable|string $dirs, int $mode = 0o777): void
	{
		throw new IOException('mkdir failed');
	}
}

/**
 * A Filesystem subclass that always fails on mirror.
 *
 * @internal
 */
final class FailingMirrorFilesystem extends Filesystem
{
	/**
	 * {@inheritDoc}
	 *
	 * @param mixed        $iterator the iterator
	 * @param array<mixed> $options  the mirror options
	 */
	public function mirror(string $originDir, string $targetDir, mixed $iterator = null, array $options = []): void
	{
		throw new IOException('mirror failed');
	}
}
