<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Service\Uuid;

use OneCMS\Base\Domain\Service\Uuid\UuidServiceInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UuidService is helps you to generate uuids for your objects.
 * This is depended on external library "ramsey/uuid".
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class UuidService implements UuidServiceInterface
{
    /**
     * @var UuidInterface
     */
    private UuidInterface $uuid;

    /**
     * UuidService constructor.
     *
     * @param string|null $uuid
     */
    public function __construct(?string $uuid = null)
    {
        if (!is_null($uuid)) {
            $this->validate($uuid);

            $this->uuid = $this->generateFromString($uuid);
        } else {
            $this->uuid = $this->generate();
        }
    }

    /**
     * @param string $uuid
     */
    private function validate(string $uuid): void
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidUuidException();
        }
    }

    /**
     * @inheritDoc
     */
    public function generateFromString(string $uuid): UuidInterface
    {
        return Uuid::fromString($uuid);
    }

    /**
     * @inheritDoc
     */
    public function generate(): UuidInterface
    {
        return Uuid::uuid4();
    }

    /**
     * @return UuidInterface
     */
    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }
}
