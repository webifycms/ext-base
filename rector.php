<?php

declare(strict_types=1);

use Webify\Tools\Rector;

return (new Rector())->initialize([
	__DIR__ . '/src',
	__DIR__ . '/test',
]);
