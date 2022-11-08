<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;
final class EnergyFacade
{
	use Nette\SmartObject;

	private const
		TableFood = 'food',
        TableSport = 'sport',
		ColumnId = 'id',
		ColumnKcal = 'kcal',
        ColumnSport = 'sport',
        ColumnFood = 'food',
        ColumnUserId = 'user_id';

	private Nette\Database\Explorer $database;


	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

    public function getAllSport(int $userId)
    {
        return $this->database
			->table('sport')
			->where('user_id', $userId);
    }
    
    public function getAllFood(int $userId)
    {
        return $this->database
			->table('food')
			->where('user_id', $userId);
    }

    public function getSportById(int $sportId)
	{
		$sport = $this->database
				 ->table('users')
				 ->get($sportId);
		return $sport;
		bdump($sportId);
	}

    public function getFoodById(int $foodId)
	{
		$food = $this->database
				 ->table('food')
				 ->get($foodId);
		return $food;
		bdump($foodId);
	}

    public function insertFood($data, $userId)
	{
		$food = $this->database
			->table('food')
			->insert([
				self::ColumnFood => $data["food"],
				self::ColumnKcal => $data["kcal"],
				self::ColumnUserId => $userId
			]);
		return $food;
	}

    public function insertSport($data, $userId)
	{
		$sport = $this->database
			->table('sport')
			->insert([
				self::ColumnSport => $data["sport"],
				self::ColumnKcal => $data["kcal"],
				self::ColumnUserId => $userId
			]);
		return $sport;
	}
}