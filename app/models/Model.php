<?php
namespace Laurent\App\Models;

class Model
{
     protected $_db;

    /**
     * Connect to the database
     * @return mixed
     */
    public function dbConnect()
    {

        $_db = new \PDO('mysql:host=cvktne7b4wbj4ks1.chr7pe7iynqr.eu-west-1.rds.amazonaws.com;dbname=lzm2fynkwt6ncb8q;charset=utf8', 'kl865flmarnj0icr', 'itj5r5cgjuhc59jz');
        //$_db = new \PDO('mysql:host=localhost;dbname=my_blog;charset=utf8', 'root', '');

        $_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
        return $this->_db = $_db;
    }

    /**
     * @param $length
     * @return string
     */
    protected function str_random($length)
    {
        $alphabet = "azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN01234567849!$";

        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }
}
