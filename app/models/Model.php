<?php 
class Model
{
    protected $_db;

    /**
     *
     */
    protected function setDB()
    {
        $this->_db = new PDO('mysql:host=localhost;dbname=my_blog;charset=utf8', 'root', '');
		$this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @return mixed
     */
    protected function getDB()
    {
        if($this->_db == NULL)
        {
            return $this->_db;
        }
    }

    /**
     * @param $table
     * @param $obj
     * @return array
     */
    protected function getAll($table, $obj)
	{
		$var = [];
		$req = $this->_db->prepare('SELECT * FROM' .$table. 'ORDER BY id desc');
		$req->execute();
		while($data = $req->fetch(PDO::FETCH_ASSOC))
		{
			$var[] = new $obj($data);
		}
		return $var;
		$req->closeCursor();
	}
}
