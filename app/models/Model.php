<?php 
class Model
{
    protected $_db;

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
