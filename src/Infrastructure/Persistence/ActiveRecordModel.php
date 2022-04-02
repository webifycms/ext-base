<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Persistence;

use yii\db\ActiveRecord;

/**
 * Undocumented class
 */
abstract class ActiveRecordModel extends ActiveRecord
{
    final const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i:s';
    
    /**
     * Sets the entity.
     *
     * @param mixed $entity
     */
    abstract public function setEntity($entity): void;
}
