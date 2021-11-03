<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Persistence\Service;

use OneCMS\Base\Domain\Service\IdentityServiceInterface;

/**
 * Class DatabaseIdService
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class DatabaseIdService implements IdentityServiceInterface
{
    /**
     * The database id.
     *
     * @var integer|null
     */
    private ?int $databaseId;

    /**
     * The DatabaseIdService constructor.
     *
     * @param integer|null $databaseId
     */
    public function __construct(?int $databaseId = null)
    {
        if (!is_null($databaseId)) {
            $this->validate($databaseId);
        }

        $this->databaseId = $databaseId;
    }

    /**
     * Validate the database databaseId
     *
     * @param integer $databaseId
     * @return void
     */
    private function validate(int $databaseId): void
    {
        if ($databaseId <= 0) {
            throw new DatabaseIdException();
        }
    }

    /**
     * Returns the databaseId.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->databaseId;
    }
}
