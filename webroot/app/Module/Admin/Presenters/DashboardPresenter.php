<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use Nette;


final class DashboardPresenter extends BasePresenter
{

	public function renderDefault(int $page = 1): void
	{
		// Zjistíme si celkový počet publikovaných článků
		$usersCount = $this->userFacade->getUsersCount();

		// Vyrobíme si instanci Paginatoru a nastavíme jej
		$paginator = new Nette\Utils\Paginator;
		$paginator->setItemCount($usersCount); // celkový počet článků
		$paginator->setItemsPerPage(10); // počet položek na stránce
		$paginator->setPage($page); // číslo aktuální stránky

		// Z databáze si vytáhneme omezenou množinu článků podle výpočtu Paginatoru
		$users = $this->userFacade->findPublishedusers($paginator->getLength(), $paginator->getOffset());

		// kterou předáme do šablony
		$this->template->users = $users;
		// a také samotný Paginator pro zobrazení možností stránkování
		$this->template->paginator = $paginator;
	}
}
