<?php
namespace Laurent\App\Models;

use Laurent\App\Models\Model;
use Laurent\App\Models\Comments;

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
        $request->closeCursor();
    }
   
    public function delete(Comments $comments)
    {
        $this->_db->exec('DELETE FROM comments WHERE id = '.(int) $comments->id());
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
        $request->bindValue(':id', (int) $_GET['commentUpdate'], \PDO::PARAM_INT);

        $request->execute();
        $request->closeCursor();
    }

    /**
     * @return mixed
     */
    public function getListComments($ref)
    {
        $request = $this->_db->query('SELECT DISTINCT comments.id, comments.author, comments.content, DATE_FORMAT(comments.addDate, \'%d/%m/%Y à %Hh%i\') AS addDate, DATE_FORMAT(comments.updateDate, \'%d/%m/%Y à %Hh%i\') AS updateDate FROM comments JOIN posts WHERE  comments.validationComment = 1 AND comments.post_id = ' . (int)$ref . ' ORDER BY addDate DESC');
        
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
        $request = $this->_db->prepare('SELECT id, author, content, DATE_FORMAT(addDate, \'%d/%m/%Y à %Hh%i\') AS addDate, DATE_FORMAT(updateDate, \'%d/%m/%Y à %Hh%i\') AS updateDate FROM comments WHERE id = :id');
        $request->bindValue(':id', $id);

        $request->execute();

        $data = $request->fetch();
        $comment = new Comments($data);
        $request->closeCursor();
        if(($_SESSION['rank'] == 2) || ($_SESSION['rank'] == 1 && $_SESSION['username'] == $comment->author()))
        {
            return $comment;            
        }
    } 

    public function count()
    {
        return $this->_db->query('SELECT count(*) FROM comments WHERE validationComment = 0')->fetchColumn();
    }  

    public function validateCommentList()
    {
        $request = $this->_db->query('SELECT id, author, content, DATE_FORMAT(addDate, \'%d/%m/%Y à %Hh%i\') AS addDate FROM comments WHERE validationComment = 0');

        $validateComment = [];

        while($data = $request->fetch())
        {
            $validateComment[] = new Comments($data);
        }
        $request->closeCursor();
        return $validateComment;
    }

    Public function turnValidateCommentOk(Comments $comments)
    {
        $request = $this->_db->prepare('UPDATE comments SET validationComment = :validationComment WHERE id = :id');

        $request->bindValue(':id', $comments->id(), \PDO::PARAM_INT);
        $request->bindValue(':validationComment', 1, \PDO::PARAM_INT);

        $request->execute();
        $request->closeCursor();
    }

    public function turnNoValidateComment(Comments $comments)
    {        
        $this->_db->exec('DELETE FROM comments WHERE id = '.(int) $comments->id());        
    }

    public function getCommentByUserId()
    {
        $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '';
        $request = $this->_db->prepare('SELECT DISTINCT comments.id, comments.author, comments.content, DATE_FORMAT(comments.addDate, \'%d/%m/%Y à %Hh%i\') AS addDate, DATE_FORMAT(comments.updateDate, \'%d/%m/%Y à %Hh%i\') AS updateDate, posts.title, posts.id as chapo FROM comments JOIN posts WHERE comments.author = :author AND posts.id = comments.post_id ORDER BY addDate DESC');
        $request->bindValue(':author', $username);

        $request->execute();

        $commentByUserId = [];

        while($data = $request->fetch())
        {
            $commentByUserId[] = new Comments($data);
        }
        $request->closeCursor();
        if(isset($commentByUserId))
        {
            return $commentByUserId;
        }
    }

    public function getAllComments()
    {
         $request = $this->_db->query('SELECT DISTINCT id, author, content, DATE_FORMAT(addDate, \'%d/%m/%Y à %Hh%i\') AS addDate, DATE_FORMAT(updateDate, \'%d/%m/%Y à %Hh%i\') AS updateDate FROM comments ORDER BY addDate DESC');

        $allComments = [];

        while ($data = $request->fetch())
        {
            $allComments[] = new Posts($data);
        }

        $request->closeCursor();
        return $allComments;
    }
}
