<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Theme;

use yii\base\Theme as BaseTheme;

/**
 * Class Theme.
 *
 * @version 0.0.1
 *
 * @since   0.0.1
 *
 * @author  Mohammed Shifreen
 *
 * @property string $id
 */
class Theme extends BaseTheme implements ThemeInterface
{
	private string $id;

	/**
	 * {@inheritDoc}
	 */
	public function init(): void
	{
		set_alias('@Theme', $this->getBasePath());

		if (empty($this->id)) {
			$explode  = explode('/', $this->getBasePath());
			$this->id = end($explode);
		}

		parent::init();
	}

	public function getId(): string
	{
		return $this->id;
	}
}
