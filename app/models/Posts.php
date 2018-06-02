<?php
class Posts
{
	/**
	 * 
	 * @var array
	 * @access protected
	 */
	protected  $errors = [];

	/**
	 * 
	 * @var int
	 * @access protected
	 */
	protected $id;

	/**
	 * 
	 * @var string
	 * @access protected
	 */
	protected  $author;

	/**
	 * 
	 * @var string
	 * @access protected
	 */
	protected  $title;

	/**
	 * 
	 * @var string
	 * @access protected
	 */
	protected  $content;

	/**
	 * 
	 * @var datetime
	 * @access protected
	 */
	protected  $addDate;

	/**
	 * 
	 * @var string
	 * @access protected
	 */
	protected  $updateDate;

	/**
	*Constantes concernées par les erreurs pendant l'execution d'une méthode.
	*/
	const INVAILABLE_AUTHOR = 1;
	const INVAILABLE_TITLE = 2;
	const INVAILABLE_CONTENT = 3;

	/**
	 * @access public
	 * @param array $values 
	 * @return void
	 */

	public function __construct($values = []) 
	{
		if(!empty($values))
		{
			$this->hydrate($values);
		}
	}

	/**
	 * @access public
	 * @param array $data 
	 * @return void
	 */

	public function hydrate($datas) 
	{
		foreach ($datas as $key => $value)
		{
			$method = 'set'.ucfirst($key);

			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
		}
	}

	 /**
	   * Méthode permettant de savoir si l'article est nouveau.
	   * @return bool
	   */
	  public function isNew()
	  {
	    return empty($this->id);
	  }

	/**
	 * @access public
	 * @return bool
	 */

	public function isValable() 
	{
		return !(empty($this->author) || empty($this->title) || empty($this->content));
	}


/////////////////////////////////////////////////////////////
//////////////////////SETTERS////////////////////////////////
/////////////////////////////////////////////////////////////

	/**
	 * @access public
	 * @param int $id 
	 * @return void
	 */

	public function setId($id) 
	{
		{
			$this->id = (int) $id;
		}
	}

	/**
	 * 					
	 * @access public
	 * @param string $author 
	 * @return void
	 */

	public function setAuthor($author) 
	{
		if(is_string($author) && strlen($author) <= 50 && !empty($author))
		{
			$this->author = $author;			
		}
		else
		{
			$this->errors[] = self::INVAILABLE_AUTHOR;
		}
	}

	/**
	 * @access public
	 * @param string $title 
	 * @return void
	 */

	public function setTitle($title) 
	{
		if(is_string($title) && strlen($title) <= 50 && !empty($title))
		{
			$this->title = $title;			
		}
		else
		{
			$this->errors[] = self::INVAILABLE_TITLE;
		}
	}

	/**
	 * @access public
	 * @param string $content 
	 * @return void
	 */

	public function setContent($content) 
	{
		if(is_string($content) && strlen($content) && !empty($content))
		{
			$this->content = $content;			
		}
		else
		{
			$this->errors[] = self::INVAILABLE_CONTENT;
		}
	}

	/**
	 * @access public
	 * @param datetime $addDate 		
	 * @return void
	 */

	public function setAddDate(\DateTime $addDate) 
	{
		$this->addDate = $addDate;
	}

	/**
	 * @access public
	 * @param datetime $updateDate 
	 * @return void
	 */

	public function setUpdateDate(\DateTime $updateDate) 
	{
		$this->updateDate = $updateDate;
	}

/////////////////////////////////////////////////////////////
//////////////////////GETTERS////////////////////////////////
/////////////////////////////////////////////////////////////

	/**
	 * @access public
	 * @return array
	 */

	public function errors() 
	{
		return $this->errors;
	}

	/**
	 * @access public
	 * @return int
	 */

	public function id() 
	{
		return (int)$this->id;
	}

	/**
	 * @access public
	 * @return string
	 */

	public function author() 
	{
		return $this->author;
	}

	/**
	 * @access public
	 * @return string
	 */

	public function title() 
	{
		return $this->title;
	}

	/**
	 * @access public
	 * @return string
	 */

	public function content() 
	{
		return $this->content;
	}

	/**
	 * @access public
	 * @return datetime
	 */

	public function addDate() 
	{
		return $this->addDate;
	}

	/**
	 * @access public
	 * @return datetime
	 */

	public function updateDate() 
	{
		return $this->updateDate;
	}
}
