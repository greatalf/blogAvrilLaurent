<?php
namespace Laurent\App\Models;

use Laurent\App\Models\Model;
use Laurent\App\Models\Posts;

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
		$author = htmlspecialchars($_POST['post_author']);
		$title = htmlspecialchars($_POST['post_title']);
		$chapo = htmlspecialchars($_POST['post_chapo']);
		$content = htmlspecialchars($_POST['post_content']);

		$request = $this->_db->prepare('INSERT INTO posts(author, title, chapo, content, addDate, updateDate) VALUES(:author, :title, :chapo, :content, NOW(), NULL)');

		$request->bindValue(':author', $author);
		$request->bindValue(':title', $title);
		$request->bindValue(':chapo', $chapo);
		$request->bindValue(':content', $content);

		$request->execute();
		$request->closeCursor();
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
		$request->bindValue(':id', htmlspecialchars($_GET['postUpdate']), \PDO::PARAM_INT);

		$request->execute();
		$request->closeCursor();
	}

    /**
     * @see PostsManager::delete()
     * @param $id
     */
	public function delete(Posts $posts)
	{
		$this->_db->exec('DELETE FROM posts WHERE id = '.(int) $posts->id());
		$this->_db->exec('DELETE FROM comments WHERE post_id = '.(int) $posts->id());
	}

    /**
     * @see PostsManager::getList()
     * @param int $debut
     * @param int $limite
     * @return array
     */
	public function getList($debut = -1, $limite = -1)
	{
		$sql = 'SELECT id, author, title, chapo, content, DATE_FORMAT(addDate, \'%d/%m/%Y à %Hh%i\') AS addDate, DATE_FORMAT(updateDate, \'%d/%m/%Y à %Hh%i\') AS updateDate FROM posts ORDER BY id DESC';

		if($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}

		$request = $this->_db->query($sql);
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
		$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
		$request = $this->_db->prepare('SELECT id, author, title, chapo, content, DATE_FORMAT(addDate, \'%d/%m/%Y à %Hh%i\') AS addDate, DATE_FORMAT(updateDate, \'%d/%m/%Y à %Hh%i\') AS updateDate FROM posts WHERE id = :id');
		$request->bindValue(':id', $post_id);
		$request->execute();

		$data = $request->fetch();
		$post = new Posts($data);
		$request->closeCursor();

		return $post;
	}

	public function count()
	{
		return $this->_db->query('SELECT count(*) FROM posts')->fetchColumn();
	}
}
