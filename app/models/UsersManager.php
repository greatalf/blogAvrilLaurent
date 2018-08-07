<?php

namespace Laurent\App\Models;

use Laurent\App\Models\Model;
use Laurent\App\Models\Users;
use Laurent\App\Service\Security;
use Laurent\App\Service\Flash;


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
			$request = $this->_db->prepare('INSERT INTO users (lastname, firstname, email, username, password, rank, confirmation_token) VALUES(?, ?, ?, ?, ?, ?, ?)');

			$password = password_hash(htmlspecialchars($_POST['regist_password']), PASSWORD_BCRYPT);
			$token = parent::str_random(60);
			$_SESSION['token'] = $token;

			$request->execute([
				htmlspecialchars($_POST['regist_lastname']),
				htmlspecialchars($_POST['regist_firstname']),
				htmlspecialchars($_POST['regist_email']),
				htmlspecialchars($_POST['regist_username']),
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
		$req = $this->_db->exec('DELETE FROM users WHERE id = '.(int) $users->id());
	}    

    /**
     * @see UsersManager::update()
     * @param Users $users
     */
	public function update(Users $users)
	{
		$password = password_hash(htmlspecialchars($users->password()), PASSWORD_BCRYPT);
		
		$request = $this->_db->prepare('UPDATE users SET lastname = :lastname, firstname = :firstname, email = :email, username = :username, password = :password WHERE id = :id');

		$request->bindValue(':lastname', $users->lastname());
		$request->bindValue(':firstname', $users->firstname());
		$request->bindValue(':email', $users->email());
		$request->bindValue(':username', $users->username());
		$request->bindValue(':password', $password);
		$request->bindValue(':id', $users->id(), \PDO::PARAM_INT);

		$request->execute();
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

	public function getUserInfos($id)
	{
		$request = $this->_db->prepare('SELECT * FROM users WHERE id = ?');
		$request->execute(array($id));

		$data = $request->fetch();
		$userInfos = new Users($data);

		return $userInfos;
	}

	public function checkUser()
	{
		if(isset($_GET['user_id']))
		{
			$request = $this->_db->prepare('SELECT * FROM users WHERE id = ? AND confirmation_token = NULL');
			$request->execute(array($_GET['user_id']));
			$data = $request->fetch();
			$user = new Users($data);

		    $request->closeCursor();
		    return $user;
		}

		if(isset($_POST['connect_email']) && isset($_POST['connect_pass']))
		{
			$connectEmail = htmlspecialchars($_POST['connect_email']);
			$password = htmlspecialchars($_POST['connect_pass']);

			$request = $this->_db->prepare('SELECT * FROM users WHERE email = ?');
			$request->execute(array($connectEmail));
			$data = $request->fetch();
			if(password_verify($password, $data['password']))
			{				
				$user = new Users($data);
		        $request->closeCursor();
		        return $user;
			}
		}
	}

	public function checkUserById()
	{
		$user_id = htmlspecialchars($_GET['user_id']);
		$request = $this->_db->prepare('SELECT * FROM users WHERE id = ?');
		$request->execute(array($user_id));
		$data = $request->fetch();
		$user = new Users($data);

	    $request->closeCursor();
	    return $user;
	}

	public function checkEmail()
	{
		if(isset($_POST['regist_email']))
		{
			$regist_email = htmlspecialchars($_POST['regist_email']);

			$request = $this->_db->prepare('SELECT count(*) FROM users WHERE email = ?');
			$request->execute([$regist_email]);
			$countEmail = $request->fetchColumn();

			return $countEmail;
		}
		if(isset($_POST['profil_email']))
		{
			$profil_email = htmlspecialchars($_POST['profil_email']);

			$request = $this->_db->prepare('SELECT count(*) FROM users WHERE email = ?');
			$request->execute([$profil_email]);
			$countEmail = $request->fetchColumn();

			return $countEmail;
		}
	}

		public function checkPseudo()
	{
		if(isset($_POST['regist_username']))
		{
			$regist_username = htmlspecialchars($_POST['regist_username']);

			$request = $this->_db->prepare('SELECT count(*) FROM users WHERE username = ?');
			$request->execute([$regist_username]);
			$countPseudo = $request->fetchColumn();

			return $countPseudo;
		}
		if(isset($_POST['profil_username']))
		{
			$profil_username = htmlspecialchars($_POST['profil_username']);

			$request = $this->_db->prepare('SELECT count(*) FROM users WHERE username = ?');
			$request->execute([$profil_username]);
			$countPseudo = $request->fetchColumn();

			return $countPseudo;
		}
	}

	public function confirmUser()
	{
		$user_id = htmlspecialchars($_GET['user_id']);
		$token = htmlspecialchars($_GET['confirmation_token']);

		$request = $this->_db->prepare('SELECT * FROM users WHERE id = ? AND confirmation_token = ?');
		$request->execute([$user_id, $token]);

		$data = $request->fetch();
		$user = new Users($data);

		return $user;
	}

	public function userIsConfirmed()
	{
		$user_id = htmlspecialchars($_GET['user_id']);

		$request = $this->_db->prepare('UPDATE users SET confirmation_token = NULL, confirmedAt = NOW() WHERE id = ?')->execute([$user_id]);
	}

	public function checkMailExistance()
	{
		$connectEmail = htmlspecialchars($_POST['connect_email']);

		$request = $this->_db->prepare('SELECT * FROM users WHERE email = ?');
		$request->execute(array($connectEmail));
		$data = $request->fetch();

		$user = new Users($data);
        $request->closeCursor();
        return $user;
	}

	public function makeConnexionOfUser()
	{
		$user = $this->checkUser();
		
		if($user != NULL && !isset($_SESSION['auth']))
		{
			if($user->confirmedAt() != NULL)
			{
				$this->_security = new Security;
				//defuse the bruteforce checking
				$this->deleteIp($this->_security->getIp());
				// Initialisation des datas

				$_SESSION['lastTime'] = time();

				$_SESSION['auth'] = 1;
				$_SESSION['id'] = $user->id();
				$_SESSION['lastname'] = $user->lastname();
				$_SESSION['firstname'] = $user->firstname();
				$_SESSION['email'] = $user->email();
				$_SESSION['username'] = $user->username();
				$_SESSION['password'] = $user->password();
				$_SESSION['rank'] = $user->rank();
				$_SESSION['tokenCsrf'] = md5(time()*rand(1,1000));
			}
			FLASH::setFlash('Validez votre adresse email avant votre première connexion');
			header('Location: connexion');
			exit();
		}
	}

	public function count()
	{
		return $this->_db->query('SELECT count(*) FROM users')->fetchColumn();
	}

	public function getUsersList()
	{
		$request = $this->_db->query('SELECT id, lastname, firstname, email, username, password, rank, DATE_FORMAT(confirmedAt, \'%d/%m/%Y à %Hh%i\') AS confirmedAt FROM users');

		$allUsers = [];

		while ($data = $request->fetch())
		{
			$allUsers[] = new Users($data);
		}

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

	public function upgradeUser(Users $users)
	{
		$request = $this->_db->prepare('UPDATE users SET rank = :rank WHERE id = :id');

		$request->bindValue(':id', $users->id(), \PDO::PARAM_INT);
		$request->bindValue(':rank', 2);


		$request->execute();
		$request->closeCursor();
	}

	public function downgradeUser(Users $users)
	{
		$request = $this->_db->prepare('UPDATE users SET rank = :rank WHERE id = :id');

		$request->bindValue(':id', $users->id(), \PDO::PARAM_INT);
		$request->bindValue(':rank', 1);

		$request->execute();
		$request->closeCursor();
	}

	public function addIp($ip)
	{
		$request = $this->_db->prepare('INSERT INTO connect (userIp) VALUES(?)')->execute(array($ip));
	}

	public function deleteIp($ip)
	{
		$this->_db->prepare('DELETE FROM connect WHERE userIp = ?')->execute(array($ip));
	}

	public function bruteForceTest($ip)
    {
    	$request = $this->_db->prepare("SELECT * FROM connect WHERE userIp = ?");

		$request->execute(array($ip));
		$request->closeCursor();
		return $request->rowCount();
    }
}
