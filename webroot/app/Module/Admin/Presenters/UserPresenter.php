<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\UserFacade;
use App\Model\EnergyFacade;
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

    public function renderDetail(int $userId): void
	{
        $users = $this->userFacade->getUserById($userId);
        $this->template->users = $users;
		$this->template->sports = $this->energyFacade->getAllSport($userId);
		$this->template->foods = $this->energyFacade->getAllFood($userId);
	}
}