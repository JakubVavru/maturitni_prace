<?php
namespace App\Module\Front\Presenters;

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;
//use App\Model\UserFacade;
use Nette\Application\UI\Presenter;
use App\Model\GoogleUserFacade;
use Nette\Application\UI\Form;

class LoginPresenter extends Presenter
{
	/** @var Google */
	private $google;
	//private UserFacade $userFacade;
    private $userRepository;

    public function __construct(Google $google, GoogleUserFacade $userRepository, /*UserFacade $userFacade*/) {
        $this->userRepository = $userRepository;
	  //$this->userFacade = $userFacade;
        $this->google = $google;
    }

	public function renderIn(): void
	{
		$authorizationUrl = $this->google->getAuthorizationUrl([
			'redirect_uri' => $this->link('//google'),
		]);

		$this->getSession(Google::class)->state = $this->google->getState();
		$this->redirectUrl($authorizationUrl);
	}


	public function actionGoogle(): void
	{
		$error = $this->getParameter('error');
		if ($error !== null) {
			$this->flashMessage('... google login error ...', 'error');
			$this->redirect('Login:in');
		}

		$state = $this->getParameter('state');
		$stateInSession = $this->getSession(Google::class)->state;
		if ($state === null || $stateInSession === null || ! \hash_equals($stateInSession, $state)) {
			$this->flashMessage('... invalid CSRF token ...', 'error');
			$this->redirect('Login:in');
		}

		// reset CSRF protection, it has done its job
		unset($this->getSession(Google::class)->state);

		$accessToken = $this->google->getAccessToken('authorization_code', [
			'code' => $this->getParameter('code'),
			'redirect_uri' => $this->link('//google'),
		]);

		try {
			/** @var GoogleUser $googleUser */
			$googleUser = $this->google->getResourceOwner($accessToken);
		} catch (\Throwable $e) {
			$this->flashMessage('... cannot retrieve user profile ...', 'error');
			$this->redirect('Login:in');
		}

		$googleId = $googleUser->getId();
		if ($user = $this->userRepository->findByGoogleId($googleId)) {
			// found existing user by googleId, login and redirect
			$this->user->login($user["username"], $googleUser->getId());
			$this->redirect('Homepage:');
		}

		$googleEmail = $googleUser->getEmail();
		if ($user = $this->userRepository->findByEmail($googleEmail)) {
			// found existing user with the same email, error and force them to login using password
			$this->flashMessage('... somebody already signed up with given email ...', 'error');
			$this->redirect('Login:in');
		}

		// new user, register them, login and redirect
		$user = $this->userRepository->registerFromGoogle($googleUser);
		$this->user->login($user["username"], $user["password"]);
		$this->redirect('Homepage:');
	}
/*
	// SignPresenter

	

	protected function createComponentSignInForm(): Form //Přihlašovací formulář
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
		bdump($data);
		try {
			$this->getUser()->login($data->username, $data->password);
			$this->redirect('Homepage:');
		} catch (\Nette\Security\AuthenticationException $e) {
			$form->addError('Nesprávné přihlašovací jméno nebo heslo.');
		}
	}
	public function actionOut(): void //Funkce odhlášení
	{
		$this->getUser()->logout();
		$this->flashMessage('Odhlášení bylo úspěšné.');
		$this->redirect('Homepage:');
	}
	public function createComponentRegisterForm(): Form //Registrační Formulář
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
		$this->flashMessage('Změna úspěšná.', 'success');
		$this->redirect('Homepage:default');
	} */
}