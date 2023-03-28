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

    public function renderList(int $page = 1): void
	{

				// Vytáhneme si publikované články
				$users = $this->userFacade->getAll();

				// a do šablony pošleme pouze jejich část omezenou podle výpočtu metody page
				$lastPage = 0;
				$this->template->users = $users->page($page, 9, $lastPage);
		
				// a také potřebná data pro zobrazení možností stránkování
				$this->template->page = $page;
				$this->template->lastPage = $lastPage;
		
				/*$this->template->foods = $this->foodFacade->getAll();*/
				bdump($users);
			}
	}