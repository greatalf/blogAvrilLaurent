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
        $sql = 'SELECT DISTINCT comments.post_id, comments.author, comments.content, comments.addDate, comments.updateDate FROM comments JOIN posts WHERE comments.post_id = ' . $ref . ' ORDER BY addDate DESC';

        $request = $this->_db->query($sql);
        $request->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'Comments');
        $listComments = $request->fetchAll();

        //Implémentation des dates d'ajout et de modification en temps qu'instances de DateTime.
        foreach ($listComments as $comments)
        {
            $comments->setAddDate(new \DateTime($comments->addDate()));
            $comments->setUpdateDate(new \DateTime($comments->updateDate()));
        }

        $request->closeCursor();

        return $listComments;
    }

    /**
     * @see CommentsManager::add()
     * @param Comments $comments
     */
    protected function add(Comments $comments)
    {
        $request = $this->_db->prepare('INSERT INTO comments(author, title, content, addDate, updateDate) VALUES(/*:author*/?, /*:title*/?, /*:content*/?, NOW(), NOW())');

        $request = ([
            $comments->title(),
            $comments->author(),
            $comments->content()
        ]);

        $request->execute();
    }
}
