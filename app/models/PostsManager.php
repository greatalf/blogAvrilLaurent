<?php
namespace Laurent\App\Models;

use Laurent\App\Models\Posts;

require_once 'app/models/Posts.php';
require_once 'app/models/Model.php';

class PostsManager extends Model
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
		$_db = $this->dbConnect();
		$this->_db = $_db;
	}

    /**
     * @see PostsManager::add()
     * @param Posts $posts
     */
	public function add(Posts $posts)
	{
		$title = htmlspecialchars($_POST['post_title']);
		$chapo = htmlspecialchars($_POST['post_chapo']);
		$content = htmlspecialchars($_POST['post_content']);

		$request = $this->_db->prepare('INSERT INTO posts(author, title, chapo, content, addDate, updateDate) VALUES(:author, :title, :chapo, :content, NOW(), NULL)');

		$request->bindValue(':author', $posts->author());
		$request->bindValue(':title', $title);
		$request->bindValue(':chapo', $chapo);
		$request->bindValue(':content', $content);

		$request->execute();
	}

	/**
     * @see PostsManager::update()
     * @param Posts $posts
     */
	public function update(Posts $posts)
	{
		$author = htmlspecialchars($_POST['post_author']);
		$title = htmlspecialchars($_POST['post_title']);
		$chapo = htmlspecialchars($_POST['post_chapo']);
		$content = htmlspecialchars($_POST['post_content']);

		$request = $this->_db->prepare('UPDATE posts SET author = :author, title = :title, chapo = :chapo, content = :content, updateDate = NOW() WHERE id = :id');
		$request->bindValue(':author', $author);
		$request->bindValue(':title', $title);
		$request->bindValue(':chapo', $chapo);
		$request->bindValue(':content', $content);
		$request->bindValue(':id', $_GET['post_update'], \PDO::PARAM_INT);

		$request->execute();
	}

    /**
     * @see PostsManager::delete()
     * @param $id
     */
	public function delete(Posts $posts)
	{
		$this->_db->exec('DELETE FROM posts WHERE id = '.(int) $posts->id());
	}

    /**
     * @see PostsManager::getList()
     * @param int $debut
     * @param int $limite
     * @return array
     */
	public function getList($debut = -1, $limite = -1)
	{
		$sql = 'SELECT id, author, title, chapo, content, addDate, updateDate FROM posts ORDER BY id DESC';

		if($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}

		$request = $this->_db->query($sql);
		// $request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Posts');
		$posts = [];

		while ($data = $request->fetch())
		{
			$posts[] = new Posts($data);
		}

        $request->closeCursor();
        return $posts;
        
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
	public function getUnique($post_id)
	{
		$request = $this->_db->prepare('SELECT id, author, title, chapo, content, addDate, updateDate FROM posts WHERE id = :id');
		$request->bindValue(':id', $post_id);
		$request->execute();

		$request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Posts');

		$post = [];

		$data = $request->fetch();

		$post[] = new Posts($data);

		//Verify the post existence
		
		// $post->setAddDate(new \DateTime($posts->addDate()));
		// $post->setUpdateDate(new \DateTime($posts->updateDate()));

		
		return $post;
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
	    throw new RuntimeException('L\'article doit être valide pour être enregistrée');
	  }
	}

	public function count()
	{
		return $this->_db->query('SELECT count(*) FROM posts')->fetchColumn();
	}
}
