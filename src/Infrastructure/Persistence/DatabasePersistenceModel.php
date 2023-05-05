<?php

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
