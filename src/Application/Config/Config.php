<?php
declare(strict_types=1);

namespace OneCMS\Base\Application\Config;

/**
 * Class Config
 *
 * @package getonecms/base
 * @version 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
final class Config implements ConfigInterface
{

    /**
     * Config constructor.
     */
    public function __construct(private array $configurations)
    {
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value): void
    {
        $this->configurations[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function get(?string $key = null)
    {
        if (is_null($key)) {
            return $this->configurations;
        }

        return $this->configurations[$key] ?? null;
    }
}
