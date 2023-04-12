<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use Nette;
use Ublaboo\DataGrid\DataGrid;

final class DashboardPresenter extends BasePresenter
{

	public function renderDefault(): void
	{

	}

	public function createComponentGrid($name)
	{
		$data = $this->userFacade->getAll();
		$grid = new DataGrid($this, $name);
		$grid->setDataSource($data);
		$grid->addColumnText('id', 'ID');
		$grid->addColumnText('username', 'Jméno');
		$grid->addColumnText('email', 'Email');
		$grid->addColumnText('sub', 'Typ účtu')
		->setRenderer(function ($item) {
			if ($item->sub == NULL) {
				return "Local";
			}
			else {
				return "Google";
			}

		});
		$grid->addAction('User:detail', 'Detail')
		->setClass('btn btn-m btn-info');
		$grid->addAction('delete', 'Smazat', 'delete!')
		->setClass('btn btn-m btn-danger');
		$grid->setItemsPerPageList([10, 100]);
		
	}

	public function handleDelete(int $id): void
    {
        $this->userFacade->deleteUser($id);
        $this->flashMessage('User was deleted.', 'success');
        $this->redirect('this');
    }
}
