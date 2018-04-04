<?php
abstract class PostsManager
{
	/**
	 * @access protected
	 * @param Post $post 
	 * @return void
	 */

	abstract protected function add(Posts $post);

	/**
	 * @access protected
	 * @param Post $post 
	 * @return void
	 */
	abstract protected function update(Posts $post);

	/**
	 * @access public
	 * @param int $id 
	 * @return void
	 */
	abstract public function delete($id);

	/**
	 * @access public
	 * @return int
	 */
	abstract public function count();

	/**
	 * @access public
	 * @return post
	 */
	abstract public function getUnique($id);
	
	/**
   * Méthode permettant d'enregistrer une news.
   * @param $news News la news à enregistrer
   * @see self::add()
   * @see self::update()
   * @return void
   */
  public function save(Posts $posts)
  {
    if ($posts->isValable())
    {
      $posts->isNew() ? $this->add($posts) : $this->update($posts);
    }
    else
    {
      throw new RuntimeException('La news doit être valide pour être enregistrée');
    }
  }

	/**
	 * @access public
	 * @param int $debut 
	 * @param int $limite 
	 * @return array
	 */
	abstract public function getList($debut = -1, $limite = -1);

}
