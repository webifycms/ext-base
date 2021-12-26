<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use OneCMS\Base\Domain\Service\UuidServiceInterface;

/**
 * Class Uuid
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class Uuid
{
    /**
     * @var string
     */
    private string $value;

    /**
     * UserUuid constructor.
     *
     * @param UuidServiceInterface $uuidService
     */
    public function __construct(UuidServiceInterface $uuidService)
    {
        $this->value = $uuidService->uuid()->toString();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
