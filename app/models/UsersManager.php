<?php

namespace Laurent\App\Models;

require_once 'app/models/Users.php';
require_once 'app/models/Model.php';


class UsersManager extends Model
{
	/**
	 * @var \PDO
	 * @access protected
	 */
	protected $_db;

    /**
     * @param PDO $_db
     */
	public function __construct(\PDO $_db)
	{
		$this->_db = $_db;
	}

    /**
     * @see UsersManager::add()
     * @param Users $users
     */
	public function add(Users $users)
	{
		if(isset($_POST['regist_lastname']) &&
			isset($_POST['regist_firstname']) &&
			isset($_POST['regist_email']) &&
			isset($_POST['regist_username']) &&
			isset($_POST['regist_password']) &&
			isset($_POST['regist_password_confirm']))
		{
			// session_start();
			$request = $this->_db->prepare('INSERT INTO users (lastname, firstname, email, username, password, rank, confirmation_token) VALUES(?, ?, ?, ?, ?, ?, ?)');

			$password = password_hash($_POST['regist_password'], PASSWORD_BCRYPT);
			$token = parent::str_random(60);
			$_SESSION['token'] = $token;

			$request->execute([
				$_POST['regist_lastname'],
				$_POST['regist_firstname'],
				$_POST['regist_email'],
				$_POST['regist_username'],
				$password,
				1,
				$token
			]);

			$newUser = $this->_db->lastInsertId();
			return $newUser;
		}
	}

    /**
     * @see UsersManager::delete()
     * @param $id
     */
	public function delete(Users $users)
	{
		$this->_db->exec('DELETE FROM users WHERE id = '.(int) $users->id());
	}

    /**
     * @see UsersManager::getUnique()
     * @param $id
     * @return mixed
     */
	public function getUnique($id)
	{
		$request = $this->_db->prepare('SELECT id, lastname, firstname, email, username, password, rank FROM users WHERE id = :id');
		$request->bindValue(':id', htmlspecialchars($id));
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Users');

		$user = $request->fetch();

		return $user;
	}

    /**
     * @see UsersManager::update()
     * @param Users $users
     */
	protected function update(Users $users)
	{
		$request = $this->_db->prepare('UPDATE users SET lastname = :lastname, firstname = :firstname, email = :email, username = :username, password = :password, rank = :rank WHERE id = :id');

		$request->bindValue(':lastname', $users->lastname());
		$request->bindValue(':firstname', $users->firstname());
		$request->bindValue(':email', $users->email());
		$request->bindValue(':username', $users->username());
		$request->bindValue(':password', $users->password());
		$request->bindValue(':rank', $users->rank());
		$request->bindValue(':id', $users->id(), \PDO::PARAM_INT);

		$request->execute();
	}

    /**
     * Méthode permettant d'enregistrer une news.
     * @param Users $users
     * @return void
     * @see self::add()
     * @see self::update()
     */
	public function save(Users $users)
	{
	  if ($users->isValable())
	  {
	    $users->isNew() ? $this->add($users) : $this->update($users);
	  }
	  else
	  {
	    throw new RuntimeException('La news doit être valide pour être enregistrée');
	  }
	}	

	public function getUserInfos($id)
	{
		$request = $this->_db->prepare('SELECT * FROM users WHERE id = ?');
			$request->execute(array($id));

			$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Users');

			$userInfos = $request->fetch();
 			return $userInfos;
	}

	public function checkUser()
	{

		if(isset($_POST['connect_email']) && isset($_POST['connect_pass']))
		{
			$connect_email = htmlspecialchars($_POST['connect_email']);
			$connect_pass = htmlspecialchars($_POST['connect_pass']);
			// $password = password_hash($_POST['connect_pass'], PASSWORD_BCRYPT);
			// $pass_verif = password_verify($_POST['connect_pass'], );
			$request = $this->_db->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
			$request->execute(array($connect_email, md5($connect_pass)));

			$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Users');
			$user = $request->fetch();

			return $user;
		}
	}

	public function confirmUser()
	{
		$user_id = htmlspecialchars($_GET['user_id']);
		$token = htmlspecialchars($_GET['confirmation_token']);

		$request = $this->_db->prepare('SELECT * FROM users WHERE id = ?');
		$request->execute([$user_id]);
		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Users');			
		$user = $request->fetch();

		return $user;
	}

	public function userIsConfirmed()
	{
		$user_id = htmlspecialchars($_GET['user_id']);

		$request = $this->_db->prepare('UPDATE users SET confirmation_token = NULL, confirmedAt = NOW() WHERE id = ?')->execute([$user_id]);
	}

	public function count()
	{
		return $this->_db->query('SELECT count(*) FROM users')->fetchColumn();
	}

	public function getUsersList()
	{
		$request = $this->_db->query('SELECT * FROM users');
		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Users');

		$allUsers = $request->fetchAll();
		$request->closeCursor();

		return $allUsers;
	}

	public function deleteUsersNoConfirmed($timeMinutes)
	{
		$timeToErase = time() - (60 * $timeMinutes);

		$count = $this->_db->query('SELECT count(*) FROM users WHERE timeToDelete != NULL')->fetchColumn();
		if($count != 0)
		{			
			$this->_db->exec('DELETE FROM users WHERE timeToDelete < ' . $timeToErase);
		}
	}
}
