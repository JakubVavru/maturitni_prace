<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use Nette;
use App\Model\UserFacade;
use Nette\Application\UI\Form;

final class SignPresenter extends Nette\Application\UI\Presenter
{
	private UserFacade $userFacade;

	public function __construct(UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}

	protected function createComponentSignInForm(): Form
	{
		$form = new Form;
		$form->addText('username', 'Uživatelské jméno:')
			->setRequired('Prosím vyplňte své uživatelské jméno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Prosím vyplňte své heslo.');

		$form->addSubmit('send', 'Přihlásit')
			->setHtmlAttribute('class', 'btn btn-primary');

		$form->onSuccess[] = [$this, 'signInFormSucceeded'];
		return $form;
	}
	public function signInFormSucceeded(Form $form, \stdClass $data): void
	{
		try {
			$this->getUser()->login($data->username, $data->password);
			$this->redirect('Homepage:');
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError('Nesprávné přihlašovací jméno nebo heslo.');
		}
	}
	public function actionOut(): void
	{
		$this->getUser()->logout();
		$this->flashMessage('Odhlášení bylo úspěšné.');
		$this->redirect('Homepage:');
	}
	public function createComponentRegisterForm(): Form
	{
		$form = new Form;
		$form->addText('username', 'Uživatelské jméno:')
			->setRequired('Prosím vyplňte nové uživatelské jméno.');

		$form->addEmail('email', 'Email:')
			->setRequired('Prosím vyplňte nové heslo.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Prosím vyplňte nové heslo.');

		$form->addSubmit('send', 'Registrovat');

		$form->onSuccess[] = [$this, 'registerFormSucceeded'];
		return $form;
	}

	public function registerFormSucceeded(Form $form, \stdClass $data)
	{
		$this->userFacade->add($data->username, $data->email, $data->password);

		$this->flashMessage('Registrace úspěšná.', 'success');
		$this->redirect('Homepage:default');
	}

	public function createComponentChangeForm(): Form
	{

		$user = $this->getUser()->getIdentity();
		$form = new Form;
		$form->addText('username', 'Uživatelské jméno:')
			->setDefaultValue($user->data['username'])
			->setRequired('Prosím vyplňte nové uživatelské jméno.');
		$form->addText('email', 'Email:')
			->setDefaultValue($user->data['email'])
			->setRequired('Prosím vyplňte nový Email.');
		$form->addPassword('password', 'Heslo:');
		$form->addSubmit('send', 'Změnit');
		$form->onSuccess[] = [$this, 'changeFormSucceeded'];
		return $form;
	}

	public function changeFormSucceeded(Form $form, \stdClass $data)
	{

		$this->userFacade->update($this->getUser()->getId(), $data);
		$this->getUser()->logout();
		$this->flashMessage('Změna úspěšná.', 'success');
		$this->redirect('Homepage:default');
	}
}