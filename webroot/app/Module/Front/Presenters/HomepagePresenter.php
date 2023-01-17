<?php

declare(strict_types=1);

namespace App\Module\Front\Presenters;

use Nette;
use App\Model\UserFacade;
use App\Model\EnergyFacade;
use Nette\Application\UI\Form;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
	private UserFacade $userFacade;
	private EnergyFacade $energyFacade;

	public function __construct(UserFacade $userFacade, EnergyFacade $energyFacade)
	{
		$this->userFacade = $userFacade;
		$this->energyFacade = $energyFacade;
	}

    public function renderList(): void
	{
        $this->template->users = $this->userFacade->getAll();

		/*
		if ($this->getUser()->isInRole('admin')) {
			$this->redirect(':Admin:Dashboard:default');
		}
		*/
	}
}