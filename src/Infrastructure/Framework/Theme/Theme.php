<?php
/**
 * The file is part of the "getonecms/ext-base", OneCMS extension package.
 *
 * @see https://getonecms.com/extension/base
 *
 * @license Copyright (c) 2022 OneCMS
 * @license https://getonecms.com/extension/base/license
 * @author Mohammed Shifreen <mshifreen@gmail.com>
 */

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Theme;

use function OneCMS\Base\Infrastructure\set_alias;

use yii\base\Theme as BaseTheme;

/**
 * Class Theme.
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

	/**
	 * {@inheritdoc}
	 */
	public function getId(): string
	{
		return $this->id;
	}
}
