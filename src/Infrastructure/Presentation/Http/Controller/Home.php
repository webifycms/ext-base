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

namespace Webify\Base\Infrastructure\Presentation\Http\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

/**
 * Renders the home page.
 */
final readonly class Home
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private Psr17Factory $factory
	) {}

	/**
	 * Handles the HTTP request/response lifecycle of the home page.
	 */
	public function __invoke(ServerRequestInterface $request): ResponseInterface
	{
		$response = $this->factory->createResponse(200);
		$body     = $this->factory->createStream('Hello, world!');

		return $response
			->withBody($body)
			->withHeader('Content-Type', 'text/plain')
		;
	}
}
