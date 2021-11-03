<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Exception;


use InvalidArgumentException;

/**
 * Class ValidationFailedException
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class ValidationFailedException extends InvalidArgumentException implements ValidationFailedExceptionInterface
{

    /**
     * @var string
     */
    private string $messageKey;

    /**
     * ValidationFailedException constructor.
     *
     * @param string $messageKey
     */
    public function __construct(string $messageKey)
    {
        $this->messageKey = $messageKey;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function getMessageKey(): string
    {
        return $this->messageKey;
    }
}