<?php
declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Theme;


use yii\base\Theme as BaseTheme;

/**
 * Class Theme
 *
 * @package getonecms/base
 * @varsion 0.0.1
 * @since   0.0.1
 * @author  Mohammed Shifreen
 *
 * @property-read string $id
 */
class Theme extends BaseTheme implements ThemeInterface
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        if (empty($this->id)) {
            $explode = explode('/', $this->getBasePath());
            $this->id = end($explode);
        }

        parent::init();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}