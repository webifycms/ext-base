<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\ValueObject;

use DateTimeInterface;

/**
 * Class Timestamp
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class Timestamp
{
    /**
     * @var DateTimeInterface
     */
    private ?DateTimeInterface $createdAt;

    /**
     * @var DateTimeInterface
     */
    private ?DateTimeInterface $updatedAt;

    /**
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface $updatedAt
     */
    public function __construct(?DateTimeInterface $createdAt = null, ?DateTimeInterface $updatedAt = null)
    {
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? $this->createdAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
