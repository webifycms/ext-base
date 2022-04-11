<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;
/**
 * Class InvalidEmailAddressException
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class InvalidEmailAddressException extends TranslatableException
{
    /**
     * Undocumented function
     */
    public function __construct(string $messageKey = 'invalid_email', array $params = [])
    {
        parent::__construct($messageKey, $params);
    }
}
