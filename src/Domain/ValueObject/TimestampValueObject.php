<?php

declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * TimestampValueObject class will be used for auiting purposes. It will help to holds created and updated datetimes.
 *
 * @package getonecms/ext-base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class TimestampValueObject
{
    /**
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface $updatedAt
     */
    public function __construct(
        private readonly DateTimeInterface $createdAt = new DateTimeImmutable(),
        private readonly DateTimeInterface $updatedAt = $this->createdAt
    ) {
    }

    /**
     * Returns created at datetime.
     *
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Returns updated at datetime.
     *
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
