<?php

declare(strict_types=1);

use OneCMS\Tools\Rector;

return (new Rector([
	__DIR__ . '/src',
	__DIR__ . '/test',
]))->initialize();