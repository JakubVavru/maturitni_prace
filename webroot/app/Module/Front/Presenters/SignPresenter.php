<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class SignPresenter extends EnergyPresenter
{
	public function renderAccount(): void
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Homepage:');
		}
        $users = $this->getUser()->getIdentity();
        $this->template->users = $users;
	}
	//Přihlašovací formulář
	protected function createComponentSignInForm(): Form 
	{
		$form = new Form;
		$form->addText('username', '')
			->setRequired('Prosím vyplňte své uživatelské jméno.')
			->setHtmlAttribute('placeholder', 'Uživatelské jméno');

		$form->addPassword('password', '')
			->setRequired('Prosím vyplňte své heslo.')
			->setHtmlAttribute('placeholder', 'Heslo');

		$form->addSubmit('send', 'Přihlásit')
			->setHtmlAttribute('class', 'btn btn-primary m-auto');

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
	//Funkce odhlášení
	public function actionOut(): void 
	{
		$this->getUser()->logout();
		$this->redirect('Homepage:');
	}
	//Registrační Formulář
	/**
	 * Registrační Formulář
	 */
	public function createComponentRegisterForm(): Form 
	{
		$form = new Form;
		$form->addText('username', '')
			->setRequired('Prosím vyplňte nové uživatelské jméno.')
			->setHtmlAttribute('placeholder', 'Uživatelské jméno');

		$form->addEmail('email', '')
			->setRequired('Prosím vyplňte nové heslo.')
			->setHtmlAttribute('placeholder', 'Email');

		$form->addPassword('password', '')
			->setRequired('Prosím vyplňte nové heslo.')
			->setHtmlAttribute('placeholder', 'Heslo');

		$form->addSubmit('send', 'Registrovat')
			->setHtmlAttribute('class', 'btn btn-primary m-auto');

		$form->onSuccess[] = [$this, 'registerFormSucceeded'];
		return $form;
	}

	public function registerFormSucceeded(Form $form, \stdClass $data)
	{
		$this->userFacade->add($data->username, $data->email, $data->password);
		$this->getUser()->login($data->username, $data->password);
		$this->redirect('Homepage:default');
	}
	// Formulář na změnu přihlašovacích údajů
	public function createComponentChangeForm(): Form //Upravení profilu
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
		$this->redirect('Homepage:default');
	}
	// Formulář na změnu váhy a výšky
	public function createComponentStatForm() : Form
	{
		$user = $this->getUser()->getIdentity();
		bdump($user);
		$form = new Form;
		$form->addInteger('weight', 'Váha: ')
			->setDefaultValue($user->data['weight'])
			->setHtmlAttribute('class', 'form-short text-center');;
		$form->addInteger('height', 'Výška')
			->setDefaultValue($user->data['height'])
			->setHtmlAttribute('class', 'form-short text-center');
		$form->addSubmit('send', 'Uložit')
			->setHtmlAttribute('class', 'btn-form btn-blue')
			->renderAsButton(True);
		$form->onSuccess[] = [$this, 'statFormSucceeded'];
		return $form;
	}
	public function statFormSucceeded(Form $form, \stdClass $data)
	{
		$this->userFacade->updateStat($this->getUser()->getId(), $data);
	}

	// formulář na změnu profilového obrázku
	public function createComponentPictureForm() : Form
	{
		$user = $this->getUser()->getIdentity();
		bdump($user);
		$form = new Form;
		$form->addUpload('picture', '')
			->addRule(Form::IMAGE, 'Thumbnail must be JPEG, PNG or GIF')
			->setHtmlAttribute('class', '');
		$form->addSubmit('send', 'Uložit')
			->setHtmlAttribute('class', 'btn-form btn-in')
			->renderAsButton(True);
		$form->onSuccess[] = [$this, 'pictureFormSucceeded'];
		return $form;
	}
	public function pictureFormSucceeded(Form $form, \stdClass $data)
	{
		if ($data->picture->isOK()) {
			$data->picture->move('picture/' . $data->picture->getSanitizedName());
			$data->picture = ('picture/' . $data->picture->getSanitizedName());
		} else {
			unset($data->picture);
		}
		$this->userFacade->updatePicture($this->getUser()->getId(), $data);
	}


	
}