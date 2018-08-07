<?php

namespace Laurent\App\Models;

class Posts
{
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
	protected  $chapo;

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
	}

	/**
	 * @access public
	 * @param string $chapo 
	 * @return void
	 */
	public function setChapo($chapo) 
	{
		if(is_string($chapo) && !empty($chapo))
		{
			$this->chapo = $chapo;			
		}
	}

	/**
	 * @access public
	 * @param string $content 
	 * @return void
	 */
	public function setContent($content) 
	{
		if(is_string($content) && !empty($content))
		{
			$this->content = $content;			
		}
	}

	/**
	 * @access public
	 * @param datetime $addDate 		
	 * @return void
	 */
	public function setAddDate($addDate) 
	{
		$this->addDate = $addDate;
	}

	/**
	 * @access public
	 * @param datetime $updateDate 
	 * @return void
	 */
	public function setUpdateDate($updateDate) 
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
	public function chapo() 
	{
		return $this->chapo;
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
