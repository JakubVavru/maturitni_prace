<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use Nette;
use App\Model\UserFacade;
use App\Model\EnergyFacade;
use Nette\Application\UI\Form;

abstract class EnergyPresenter extends Nette\Application\UI\Presenter
{
    public UserFacade $userFacade;
	public EnergyFacade $energyFacade;

	public function __construct(UserFacade $userFacade, EnergyFacade $energyFacade)
	{
		$this->userFacade = $userFacade;
		$this->energyFacade = $energyFacade;
	}

    public function createComponentSportForm(): Form 
	{
		$form = new Form;
		$form->addText('sport', 'Vykonaná činnost: ')
			->setRequired('Zadejte činnost');

		$form->addInteger('kcal', 'Spálené Kalorie: ')
			->setRequired('Zadejte kalorie');

		$form->addSubmit('send', 'Přidat')
			->setHtmlAttribute('class', 'btn-form btn-blue')
			->renderAsButton(True);

		$form->onSuccess[] = [$this, 'sportFormSucceeded'];
		return $form;
	}

	public function sportFormSucceeded(Form $form, array $data)
	{
		$this->energyFacade->insertSport($data, $this->user->getId());
		$this->flashMessage('Přidáno: '. $data['sport']. " | -" . $data['kcal'] . "kcal", "sport");
	}

	public function createComponentFoodForm(): Form 
	{
		$form = new Form;
		$form->addText('food', 'Přijatá potrava: ')
			->setRequired('Zadejte jídlo');

		$form->addInteger('kcal', 'Nabrané Kalorie: ')
			->setRequired('Zadejte kalorie');

		$form->addSubmit('send', 'Přidat')
			->setHtmlAttribute('class', 'btn-form btn-out')
			->renderAsButton(True);

		$form->onSuccess[] = [$this, 'foodFormSucceeded'];
		return $form;
	}

	public function foodFormSucceeded(Form $form, array $data)
	{
		$this->energyFacade->insertFood($data, $this->user->getId());
		$this->flashMessage('Přidáno: '. $data['food']. " | +" . $data['kcal'] . "kcal", "food");
	}
}