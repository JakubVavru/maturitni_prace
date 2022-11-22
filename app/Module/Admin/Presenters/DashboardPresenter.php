<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use Nette;


final class DashboardPresenter extends BasePresenter
{

	public function renderDefault(): void
	{
        $this->template->users = $this->userFacade->getAll();

	}
}
