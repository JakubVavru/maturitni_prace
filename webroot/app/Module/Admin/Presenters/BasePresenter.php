<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use Nette;
use App\Model\UserFacade;

use Nette\Application\UI\Form;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    public UserFacade $userFacade;

	public function __construct(UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}

    public function startup()
    {
        parent::startup();
        $role = $this->getUser()->getRoles();
        bdump($role);
            if (!$this->getUser()->isInRole('admin')) {
               $this->redirect(":Front:Homepage:default");
               $this->flashMessage("Sem nemáš přístup!!!");
            };
    }
}