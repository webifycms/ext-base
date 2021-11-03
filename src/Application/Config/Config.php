<?php
declare(strict_types=1);

namespace OneCMS\Base\Application\Config;


/**
 * Class Config
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 */
class Config implements ConfigInterface
{

    /**
     * @var array
     */
    private array $configurations;

    /**
     * Config constructor.
     *
     * @param array $configurations
     */
    public function __construct(array $configurations)
    {
        $this->configurations = $configurations;
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