<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Validation;

/**
 * Undocumented interface
 */
interface ValidationServiceInterface
{
    /**
     * Validate function
     *
     * @return bool
     */
    public function validate(): bool;
}
