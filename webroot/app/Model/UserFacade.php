<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
final class UserFacade implements Nette\Security\Authenticator
{
	use Nette\SmartObject;

	public const PasswordMinLength = 7;

	private const
		TableName = 'users',
		ColumnId = 'id',
		ColumnSub = 'sub',
		ColumnName = 'username',
		ColumnPicture = 'picture',
		ColumnPasswordHash = 'password',
		ColumnEmail = 'email',
		ColumnRole = 'role',
		ColumnWeight = 'weight',
		ColumnHeight = 'height';


	private Nette\Database\Explorer $database;

	private Passwords $passwords;


	public function __construct(Nette\Database\Explorer $database, Passwords $passwords)
	{
		$this->database = $database;
		$this->passwords = $passwords;
	}


	/**
	 * Performs an authentication.
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(string $username, string $password): Nette\Security\SimpleIdentity
	{
		$row = $this->database->table(self::TableName)
			->where(self::ColumnName, $username)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!$this->passwords->verify($password, $row[self::ColumnPasswordHash])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif ($this->passwords->needsRehash($row[self::ColumnPasswordHash])) {
			$row->update([
				self::ColumnPasswordHash => $this->passwords->hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::ColumnPasswordHash]);
		return new Nette\Security\SimpleIdentity($row[self::ColumnId], $row[self::ColumnRole], $arr);
	}


	/**
	 * Adds new user.
	 * @throws DuplicateNameException
	 */
	public function add(string $username, string $email, string $password): void
	{
		Nette\Utils\Validators::assert($email, 'email');
		try {
			$this->database->table(self::TableName)->insert([
				self::ColumnName => $username,
				self::ColumnPasswordHash => $this->passwords->hash($password),
				self::ColumnEmail => $email,
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}
	public function getAll()
	{
		return $this->database
			->table('users');
	}
	public function getUserById(int $userId)
	{

		$user = $this->database
				 ->table('users')
				 ->select("*")
				 ->where('id', $userId)
				 ->fetch();
		return $user;
	}
	public function update(int $userId, \stdClass $data)
	{
		if ($data->password == null) {
			$this->database->table(self::TableName)->get(['id'=>$userId])->update([
			self::ColumnName => $data->username,
			self::ColumnEmail => $data->email,
		]);
		} else {
			$this->database->table(self::TableName)->get(['id'=>$userId])->update([
			self::ColumnName => $data->username,
			self::ColumnEmail => $data->email,
			self::ColumnPasswordHash => $this->passwords->hash($data->password),
		]);
		}}
		public function updateStat(int $userId, \stdClass $data)
		{
				$this->database->table(self::TableName)->get(['id'=>$userId])->update([
				self::ColumnWeight => $data->weight,
				self::ColumnHeight => $data->height,
			]);
		}
		public function updatePicture(int $userId, \stdClass $data)
		{
				$this->database->table(self::TableName)->get(['id'=>$userId])->update([
				self::ColumnPicture => $data->picture
			]);
		}

		public function findUsers(): Nette\Database\Table\Selection
		{
			return $this->database->table('users')
			->where('username')
			->order('username');

		}
		public function getUsersCount(): int
		{
			return $this->database->fetchField('SELECT COUNT(*) FROM users WHERE username ORDER BY username');
		}

		public function deleteUser(int $id)
	{
		$this->database
			->table('users')
			->where('id', $id)
			->delete();
	}
	
}



class DuplicateNameException extends \Exception
{
}
