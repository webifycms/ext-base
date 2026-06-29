<?php

/**
 * The file is part of the "webifycms/ext-base", WebifyCMS extension package.
 *
 * @see https://webifycms.com/extension/base
 *
 * @copyright Copyright (c) 2023 - Present WebifyCMS
 * @license https://webifycms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */
declare(strict_types=1);

namespace Webify\Base\Infrastructure\Service;

use Symfony\Component\String\Slugger\AsciiSlugger;
use Webify\Base\Domain\Service\SlugifyInterface;

/**
 * SymfonyAsciiSlugify is a service that converts a string into a slug using the Symfony AsciiSlugger.
 */
final readonly class SymfonyAsciiSlugify implements SlugifyInterface
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private AsciiSlugger $slugger
	) {}

	/**
	 * {@inheritDoc}
	 */
	public function slugify(string $text): string
	{
		return $this->slugger->slug($text)->toString();
	}
}
