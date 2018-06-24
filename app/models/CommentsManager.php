<?php
namespace Laurent\App\Models;

require_once 'app/models/Comments.php';
require_once 'app/models/Model.php';

class CommentsManager extends Model
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
    public function __construct(\PDO $_db)
    {
        $this->_db = $_db;
    }

    /**
    * @see CommentsManager::add()
    * @param Comments $comments
    */
    public function add(Comments $comments)
    {
        $username = htmlspecialchars($_SESSION['username']);
        $message = htmlspecialchars($_POST['com_content']);
        $post_id = htmlspecialchars($_GET['post_id']);

        $request = $this->_db->prepare('INSERT INTO comments(author, content, addDate, post_id) VALUES(:author, :content, NOW(), :post_id)');

        $request->bindValue(':author', $username);
        $request->bindValue(':content', nl2br($message));
        $request->bindValue(':post_id', $post_id);

        $request->execute();
    }
   
    public function delete(Comments $comments)
    {
        return $this->_db->exec('DELETE FROM comments WHERE id = '.(int) $comments->id());
    }

    /**
    * @see PostsManager::update()
    * @param Posts $comments
    */

    public function update(Comments $comments)
    {
        $message = (htmlspecialchars($_POST['com_content']));

        $request = $this->_db->prepare('UPDATE comments SET content = :content, updateDate = NOW() WHERE id = :id');

        $request->bindValue(':content', nl2br($message));
        $request->bindValue(':id', $comments->id(), \PDO::PARAM_INT);

        $request->execute();
    }

    /**
     * @return mixed
     */
    public function getListComments($ref)
    {
        $request = $this->_db->query('SELECT DISTINCT comments.id, comments.author, comments.content, comments.addDate, comments.updateDate FROM comments JOIN posts WHERE comments.post_id = ' . (int)$ref . ' ORDER BY addDate DESC');

        $request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Comments');

        $listComments = [];

        while ($data = $request->fetch())
        {
            $listComments[] = new Posts($data);
        }

        $request->closeCursor();
        return $listComments;
    }

     /**
     * @see CommentsManager::getUniqueComment()
     * @param $id
     * @return mixed
     */
    public function getUniqueComment($id)
    {
        $request = $this->_db->prepare('SELECT id, author, content, addDate, updateDate FROM comments WHERE id = :id');
        $request->bindValue(':id', $id);
        $request->execute();

        $request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Comments');

        $comment = $request->fetch();
        //Verify the comment existence
        if($comment != false && $comment->updateDate() != NULL)
        {
            $comment->setAddDate(new \DateTime($comment->addDate()));
            $comment->setUpdateDate(new \DateTime($comment->updateDate()));
            return $comment;
        }
        else
        {
            $comment->setAddDate(new \DateTime($comment->addDate()));
            return $comment;    
        }
    }    
}
