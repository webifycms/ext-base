<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Model\Exception;

use OneCMS\Base\Domain\Service\Validation\ValidationFailedException;


/**
 * Class InvalidDatetimeException
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class InvalidDatetimeException extends ValidationFailedException
{
    /**
     * @param string $messageKey
     */
    public function __construct(string $messageKey = 'invalid_datetime')
    {
        parent::__construct($messageKey);
    }
}
