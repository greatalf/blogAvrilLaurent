<?php
class PostsManager_PDO extends PostsManager
{

	/**
	 * 
	 * @var PDO
	 
	 * @access protected
	 */
	protected  $db;

	private $num; /* nombre d'articles à afficher */

	/**
	* @param $bd PDO Un objet PDO
	* @return void
	*/
	public function __construct(PDO $db)
	{
		$this->db = $db;
	}

	/**
	* @see PostsManager::add() 
	*/
	protected function add(Posts $posts)
	{
		$request = $this->db->prepare('INSERT INTO posts(author, title, content, addDate, updateDate) VALUES(/*:author*/?, /*:title*/?, /*:content*/?, NOW(), NOW())');

		// $request->bindValue(':title', $posts->title());
		// $request->bindValue(':author', $posts->author());
		// $request->bindValue(':content', $posts->content());
		$request = ([
			$posts->title(),
			$posts->author(),
			$posts->content()
		]);

		$request->execute();
	}


	/**
	* @see PostsManager::delete()
	*/
	public function delete($id)
	{
		$this->db->exec('DELETE FROM posts WHERE id = '.(int) $id);
	}

	/**
	* @see PostsManager::getList()
	*/
	public function getList($debut = -1, $limite = -1)
	{
		$sql = 'SELECT id, author, title, content, addDate, updateDate FROM posts ORDER BY id DESC';

		if($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut; 
		}

		$request = $this->db->query($sql);
		$request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Posts');
		$listPosts = $request->fetchAll();

		//Implémentation des dates d'ajout et de modification en temps qu'instances de DateTime.
		foreach ($listPosts as $posts)
		{
		    $posts->setAddDate(new DateTime($posts->addDate()));
		    $posts->setUpdateDate(new DateTime($posts->updateDate()));
	    }
	    
	    $request->closeCursor();

	    return $listPosts;
	}

	/**
	*  @see PostsManager::getUnique()
	*/
	public function getUnique($id)
	{
		$request = $this->db->prepare('SELECT id, author, title, content, addDate, updateDate FROM posts WHERE id = :id');
		$request->bindValue(':id', $id);
		$request->execute();

	$request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Posts');

	$posts = $request->fetch();

	$posts->setUpdateDate(new DateTime($posts->updateDate()));
	$posts->setAddDate(new DateTime($posts->addDate()));

	return $posts;

	}

	/**
	*@see PostsManager::update()
	*/
	protected function update(Posts $posts)
	{
		$request = $this->db->prepare('UPDATE posts SET author = :author, title = :title, content = :content, updateDate = NOW() WHERE id = :id');

		$request->bindValue(':author', $posts->author());
		$request->bindValue(':title', $posts->title());
		$request->bindValue(':content', $posts->content());
		$request->bindValue(':id', $posts->id(), PDO::PARAM_INT);

		$request->execute();
	}

	public function count()
	{
		return $this->db->query('SELECT count(*) FROM posts')->fetchColumn();
	}
}
