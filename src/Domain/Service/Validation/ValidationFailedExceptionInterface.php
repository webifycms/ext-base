<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Validation;

/**
 * Interface ValidationFailedExceptionInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface ValidationFailedExceptionInterface
{
    /**
     * Returns the message key.
     *
     * @return string
     */
    public function getMessageKey(): string;

    /**
     * Returns the value of the given param if exist otherwise returns null.
     *
     * @return ?string
     */
    public function getParam(string $key): ?string;
}
