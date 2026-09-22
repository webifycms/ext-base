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

namespace Webify\Base\Infrastructure\Contract;

/**
 * The rendering contract.
 *
 * Callers hand over which extension's view they want and a relative path; resolution
 * and priority are the implementation's business.
 */
interface TemplateRendererInterface
{
	/**
	 * Renders a view for the given extension.
	 *
	 * @param string                  $templatePath e.g. 'post/view'
	 * @param array<string, mixed>    $data         variables available to the view
	 * @param null|ExtensionInterface $extension    provide the extension if the template is for extension
	 */
	public function render(string $templatePath, array $data = [], ?ExtensionInterface $extension = null): string;
}
