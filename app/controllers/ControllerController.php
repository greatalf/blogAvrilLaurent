<?php

namespace Laurent\App\Controllers;

class Controller
{
	$_db = (new Model())->dbConnect();
		$this->_postsManager = new PostsManager($_db);
		$this->_commentsManager = new CommentsManager($_db);
		$this->_usersManager = new UsersManager($_db);
}
	