<?php

declare(strict_types=1);

namespace OneCMS\Base\Infrastructure\Framework\Form;

use yii\base\Model;

/**
 * FormModel class will helpful to work with HTML forms.
 */
class FormModel extends Model
{
	/**
	 * This redirection url will be used on validation failed.
	 *
	 * @see \OneCMS\Base\Infrastructure\Framework\Controller\WebController::redirect()
	 */
	private string|array|null $redirectUrlOnValidationFailed = null;

	/**
	 * {@inheritDoc}
	 */
	public function init(): void
	{
		// if data loaded only should validate
		if ($this->load(app()->request->post())) {
			$this->validate();
		}

		parent::init();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws InvalidConfigException
	 */
	public function afterValidate(): void
	{
		parent::afterValidate();

		if ($this->hasErrors()) {
			if (app()->request->isAjax) {
				$response         = app()->response;
				$response->format = 'json';
				$response->data   = [
					'errors' => $this->getFirstErrors(),
				];
				$response->statusCode = 400;

				$response->send();
			} else {
				app()->session->setFlash($this->formName() . '_errors', $this->getFirstErrors());

				if (!empty($this->redirectUrlOnValidationFailed)) {
					app()->controller->redirect($this->redirectUrlOnValidationFailed);
				}
			}
		}
	}

	/**
	 * Set the value of redirectUrlOnValidationFailed.
	 *
	 * @see \OneCMS\Base\Infrastructure\Framework\Controller\WebController::redirect()
	 */
	public function setRedirectUrlOnValidationFailed(string|array $redirectUrlOnValidationFailed): self
	{
		$this->redirectUrlOnValidationFailed = $redirectUrlOnValidationFailed;

		return $this;
	}
}
