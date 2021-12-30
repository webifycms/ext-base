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
     * @return string
     */
    public function getMessageKey(): string;
}
