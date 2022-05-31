<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Uuid;

use OneCMS\Base\Domain\Exception\TranslatableException;

/**
 * Class InvalidUuidException
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class InvalidUuidException extends TranslatableException
{
    /**
     * Undocumented function
     */
    public function __construct(string $messageKey = 'invalid_uuid', array $arguments = [])
    {
        parent::__construct($messageKey, $arguments);
    }
}
