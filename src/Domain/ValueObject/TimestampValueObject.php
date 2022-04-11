<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Value object class for Timestamp that holding attributes of created and updated values.
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class TimestampValueObject
{
    /**
     * @var DateTimeInterface
     */
    private readonly ?DateTimeInterface $updatedAt;

    /**
     * @param DateTimeInterface $createdAt
     * @param ?DateTimeInterface $updatedAt
     */
    public function __construct(
        private readonly DateTimeInterface $createdAt = new DateTimeImmutable(),
        ?DateTimeInterface $updatedAt = null
    ) {
        $this->updatedAt = $updatedAt ?? $this->createdAt;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
