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

namespace Webify\Base\Infrastructure\Persistence;

use yii\db\ActiveRecord;

/**
 * PersistenceModel class is a base persistence model class and can extend.
 */
class DatabasePersistenceModel extends ActiveRecord
{
	final public const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i:s';
}
