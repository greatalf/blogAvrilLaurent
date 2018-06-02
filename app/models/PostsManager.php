<?php
require_once 'app/models/Posts.php';
require_once 'app/models/Model.php';

class PostsManager extends Model
{
	/**
	 * 
	 * @var \PDO
	 * @access protected
	 */
	protected $_db;

    /**
     * @param PDO $_db
     */
	public function __construct(PDO $_db)
	{
		$this->_db = $_db;
	}

    /**
     * @see PostsManager::add()
     * @param Posts $posts
     */
	protected function add(Posts $posts)
	{
		$request = $this->_db->prepare('INSERT INTO posts(author, title, content, addDate, updateDate) VALUES(/*:author*/?, /*:title*/?, /*:content*/?, NOW(), NOW())');

		$request = ([
			$posts->title(),
			$posts->author(),
			$posts->content()
		]);

		$request->execute();
	}


    /**
     * @see PostsManager::delete()
     * @param $id
     */
	public function delete($id)
	{
		$this->_db->exec('DELETE FROM posts WHERE id = '.(int) $id);
	}

    /**
     * @see PostsManager::getList()
     * @param int $debut
     * @param int $limite
     * @return array
     */
	public function getList($debut = -1, $limite = -1)
	{
		$sql = 'SELECT id, author, title, content, addDate, updateDate FROM posts ORDER BY id DESC';

		if($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}

		$request = $this->_db->query($sql);
		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Posts');
		$listPosts = $request->fetchAll();

		//Implémentation des dates d'ajout et de modification en temps qu'instances de DateTime.
		foreach ($listPosts as $posts)
		{
		    $posts->setAddDate(new \DateTime($posts->addDate()));
		    $posts->setUpdateDate(new \DateTime($posts->updateDate()));
	    }	    
	    $request->closeCursor();

	    return $listPosts;
	}

	public function getMyList($debut = -1, $limite = -1)
	{ 
		$sql = 'SELECT * FROM posts WHERE author = " . $_SESSION[\'author\'] . "';


		if($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}

		$request = $this->_db->query($sql);

		if($this->_db->query($sql)->rowCount() != 0)
		{
			if($request != false)
			{
				$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Posts');
				$listPosts = $request->fetchAll();

				// var_dump($listPosts);

				//Implémentation des dates d'ajout et de modification en temps qu'instances de DateTime.
				foreach ($listPosts as $posts)
				{
				    $posts->setAddDate(new \DateTime($posts->addDate()));
				    $posts->setUpdateDate(new \DateTime($posts->updateDate()));
			    }
			    $request->closeCursor();

			    return $listPosts;			
			}
		}
	}

    /**
     * @see PostsManager::getUnique()
     * @param $id
     * @return mixed
     */
	public function getUnique($id)
	{
		$request = $this->_db->prepare('SELECT id, author, title, content, addDate, updateDate FROM posts WHERE id = :id');
		$request->bindValue(':id', $id);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Posts');

		$post = $request->fetch();
		//Verify the post existence
		if($post != false)
		{
			$post->setUpdateDate(new \DateTime($post->updateDate()));
			$post->setAddDate(new \DateTime($post->addDate()));		
			return $post;
		}
		else
		{
			Session::setFlash('L\'article demandé est inexistant !');
			header('Location:articles');
		}
	}

    /**
     * @see PostsManager::update()
     * @param Posts $posts
     */
	protected function update(Posts $posts)
	{
		$request = $this->_db->prepare('UPDATE posts SET author = :author, title = :title, content = :content, updateDate = NOW() WHERE id = :id');

		$request->bindValue(':author', $posts->author());
		$request->bindValue(':title', $posts->title());
		$request->bindValue(':content', $posts->content());
		$request->bindValue(':id', $posts->id(), \PDO::PARAM_INT);

		$request->execute();
	}

    /**
     * Méthode permettant d'enregistrer une news.
     * @param Posts $posts
     * @return void
     * @see self::add()
     * @see self::update()
     */
	public function save(Posts $posts)
	{
	  if ($posts->isValable())
	  {
	    $posts->isNew() ? $this->add($posts) : $this->update($posts);
	  }
	  else
	  {
	    throw new RuntimeException('La news doit être valide pour être enregistrée');
	  }
	}

	public function count()
	{
		return $this->_db->query('SELECT count(*) FROM posts')->fetchColumn();
	}
}
