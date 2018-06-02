<?php
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
    public function __construct(PDO $_db)
    {
        $this->_db = $_db;
    }

    /**
     * @return mixed
     */
    public function getListComments($ref)
    {
        $sql = 'SELECT DISTINCT comments.id, comments.author, comments.content, comments.addDate, comments.updateDate FROM comments JOIN posts WHERE comments.post_id = ' . (int)$ref . ' ORDER BY addDate DESC';

        $request = $this->_db->query($sql);
        $request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Comments');
        $listComments = $request->fetchAll();

        //Implémentation des dates d'ajout et de modification en temps qu'instances de DateTime.
        foreach ($listComments as $comments)
        {
            if($comments->updateDate() != NULL)
            {
                $comments->setAddDate(new \DateTime($comments->addDate()));
                $comments->setUpdateDate(new \DateTime($comments->updateDate()));                
            }
            else
            {
                $comments->setAddDate(new \DateTime($comments->addDate()));
            }
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

    /**
    * @see CommentsManager::add()
    * @param Comments $comments
    */
    public function add($post_id)
    {
        $username = htmlspecialchars($_SESSION['username']);
        $message = htmlspecialchars($_POST['com_message']);

        $request = $this->_db->prepare('INSERT INTO comments(author, content, addDate, post_id) VALUES(?, ?, NOW(), ?)');

            $request->execute([
                $username,
                $message,
                $post_id
            ]);
    }


    public function delete($id)
    {
        return $this->_db->exec('DELETE FROM comments WHERE id = '.(int) $id);
    }

    /**
    * @see PostsManager::update()
    * @param Posts $comments
    */

    public function update($id)
    {
        $message = (htmlspecialchars($_POST['com_message']));

        $request = $this->_db->prepare('UPDATE comments SET content = :content, updateDate = NOW() WHERE id = :id');

        $request->bindValue(':content', nl2br($message));
        $request->bindValue(':id', $id);

        $request->execute();
    }
}
