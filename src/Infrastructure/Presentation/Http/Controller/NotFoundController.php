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
 * Renders the 404 Not Found page when a route is not matched.
 */
final readonly class NotFoundController
{
	/**
	 * The constructor.
	 */
	public function __construct(
		private Psr17Factory $factory
	) {}

	/**
	 * Handles the HTTP request/response lifecycle of the 404 Not Found page.
	 */
	public function __invoke(ServerRequestInterface $request): ResponseInterface
	{
		$response = $this->factory->createResponse(404);
		$body     = $this->factory->createStream('404 — Page Not Found');

		return $response
			->withBody($body)
			->withHeader('Content-Type', 'text/plain; charset=utf-8')
		;
	}
}
