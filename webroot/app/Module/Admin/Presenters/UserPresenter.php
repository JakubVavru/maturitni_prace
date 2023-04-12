<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\UserFacade;
use App\Model\EnergyFacade;
use Nette\Application\UI\Form;

use Nette;


final class UserPresenter extends Nette\Application\UI\Presenter
{
    private UserFacade $userFacade;
	private EnergyFacade $energyFacade;

	public function __construct(UserFacade $userFacade, EnergyFacade $energyFacade)
	{
		$this->userFacade = $userFacade;
		$this->energyFacade = $energyFacade;
	}

    public function renderDetail(int $id): void
	{
        $users = $this->userFacade->getUserById($id);
        $this->template->users = $users;
		$this->template->sports = $this->energyFacade->getAllSport($id);
		$this->template->foods = $this->energyFacade->getAllFood($id);
	}

	public function handleDeleteFood(int $foodId): void
    {
        $this->energyFacade->deleteFood($foodId);
        $this->redrawControl('delete');
    }

	public function handleDeleteSport(int $sportId): void
    {
        $this->energyFacade->deleteSport($sportId);
        $this->redrawControl('delete');
    }

	public function createComponentChangeForm(): Form //Upravení profilu
	{
		$userId = $this->getParameter("id");
		$user = $this->userFacade->getUserById(intval($userId));
		bdump($user);
		$form = new Form;
		$form->addText('username', 'Uživatelské jméno:')
			->setDefaultValue($user['username'])
			->setRequired('Prosím vyplňte nové uživatelské jméno.');
		$form->addText('email', 'Email:')
			->setDefaultValue($user['email'])
			->setRequired('Prosím vyplňte nový Email.');
		$form->addPassword('password', 'Heslo:');
		$form->addSubmit('send', 'Změnit');
		$form->onSuccess[] = [$this, 'changeFormSucceeded'];
		return $form;
	}

	public function changeFormSucceeded(Form $form, \stdClass $data)
	{

		$this->userFacade->update(intval($this->getParameter("id")), $data);
		$this->redrawControl('delete');
	}
}