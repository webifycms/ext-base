<?php
declare(strict_types=1);

namespace OneCMS\Base\Domain\Service\Uuid;

/**
 * Interface UuidServiceInterface
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
interface UuidServiceInterface
{

    /**
     * @return mixed
     */
    public function uuid();

    /**
     * @return mixed
     */
    public function generate();

    /**
     * @param string $uuid
     *
     * @return mixed
     */
    public function generateFromString(string $uuid);
}
