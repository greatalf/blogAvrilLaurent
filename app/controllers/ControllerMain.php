<?php
namespace Laurent\App\Controllers;

use \Laurent\App\Models\PostsManager;
use \Laurent\App\Models\CommentsManager;
use \Laurent\App\Models\UsersManager;
use \Laurent\App\Models\Users;
use \Laurent\App\Models\Model;
use \Laurent\App\Views\View;
use \Laurent\App\Service\Mail;
use \Laurent\App\Service\Security;
use \Laurent\App\Service\Profile;

class ControllerMain
{
	protected $_post;
	protected $_comment;
	protected $_user;
	protected $_view;
	protected $_db;
	protected $_postsManager;
    protected $_usersManager;
    protected $_commentsManager;
    protected $_security;
    protected $_profile;
    protected $_mail;

	public function __construct()
	{ 	
		$_db = (new Model())->dbConnect();
		$this->_db = $_db;
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
		$this->_security = new Security();
		$this->_profile = new Profile();
		$this->_mail = new Mail();
	}	
}
