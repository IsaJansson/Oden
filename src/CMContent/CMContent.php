<?php
/**
* A model for content stored in the database
* @package OdenCore
*/

class CMContent extends CObject implements IHasSQL, ArrayAccess {
	
	// Properties
	public $data;

	// Constructor
	public function __construct($id=null) {
		parent::__construct();
		if($id) {
			$this->LoadById($id); 
		} 
		else {
			$this->data = array();
		}
	}

	//Implementing ArrayAccess for $this->data
    public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
    public function offsetExists($offset) { return isset($this->data[$offset]); }
    public function offsetUnset($offset) { unset($this->data[$offset]); }
    public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }

    /** 
    * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
    * @param string $key, the string that is the key of the wanted SQL-entry in the array.
    */
    public static function SQL($key=null) {
    	$order_order = isset($args['order-order']) ? $args['order-order'] : 'ASC';
    	$order_by = isset($args['order-by']) ? $args['order-by'] : 'id';
    	$queries = array(
	      'drop table content'      => "DROP TABLE IF EXISTS Content;",
	      'create table content'    => "CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, filter TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
	      'insert content'          => 'INSERT INTO Content (key,type,title,data,filter,idUser) VALUES (?,?,?,?,?,?);',
	      'select * by id'          => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.id=?;',
	      'select * by key'         => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=?;',
	      'select * by type'        => "SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? ORDER BY {$order_by} {$order_order};",
	      'select *'                => 'SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id;',
	      'update content'          => "UPDATE Content SET key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=?;",
     	);
    	if(!isset($queries[$key])) {
      		throw new Exception("No such SQL query, key '$key' was not found.");
    	}
    	return $queries[$key];
    }	

    //Initiate database and create appropriate tables
    public function Init() {
    	try {
    	  $this->db->ExecuteQuery(self::SQL('drop table content'));
	      $this->db->ExecuteQuery(self::SQL('create table content'));
	      $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', "This is a demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
	      $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', "This is another demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
	      $this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', "This is one more demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
	      $this->db->ExecuteQuery(self::SQL('insert content'), array('home', 'page', 'Home page', "This is a demo page, this could be your personal home-page.\n\nOden is a PHP-based MVC-inspired Content management Framework.", 'plain', $this->user['id']));
	      $this->db->ExecuteQuery(self::SQL('insert content'), array('about', 'page', 'About page', "This is a demo page, this could be your personal about-page.\n\nLydia is used as a tool to educate in MVC frameworks.", 'plain', $this->user['id']));
	      $this->db->ExecuteQuery(self::SQL('insert content'), array('download', 'page', 'Download page', "This is a demo page, this could be your personal download-page.\n\nYou can download your own copy of Oden from https://github.com/isajansson/oden.", 'plain', $this->user['id']));
	      $this->db->ExecuteQuery(self::SQL('insert content'), array('bbcode', 'page', 'Page with BBCode', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://dbwebb.se]a link to dbwebb.se[/url]. You can also include images using bbcode, such as the Oden logo: [img]http://student.bth.se/~isja13/phpmvc/me/kmom05/me-sida/oden/theme/core/img/logo.png[/img]", 'bbcode', $this->user['id']));
	      $this->db->ExecuteQuery(self::SQL('insert content'), array('htmlpurify', 'page', 'Page with HTMLPurifier', "This is a demo page with some HTML code intended to run through <a href='http://htmlpurifier.org/'>HTMLPurify</a>. Edit the source and insert HTML code and see if it works.\n\n<b>Text in bold</b> and <i>text in italic</i> and <a href='http://dbwebb.se'>a link to dbwebb.se</a>. JavaScript, like this: <javascript>alert('hej');</javascript> should however be removed.", 'htmlpurify', $this->user['id']));
      	  $this->db->ExecuteQuery(self::SQL('insert content'), array('markdownextra', 'page', 'Page with Markdown Extra', "This is a demo page with Med filtret Markdown Extra kan man formattera **fet text**, *kursiv text* eller länk till [dbwebb.se](http://dbwebb.se) på ett enkelt sätt. Vanliga HTML-taggar fungerar också.", 'markdownextra', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('smartypants', 'page', 'Page with Smartypants', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://dbwebb.se]a link to dbwebb.se[/url]. You can also include images using bbcode, such as the Oden logo: [img]http://student.bth.se/~isja13/phpmvc/me/kmom05/me-sida/oden/theme/core/logo.png[/img]", 'smartypants', $this->user['id']));
          $this->db->ExecuteQuery(self::SQL('insert content'), array('make_clickable', 'page', 'Page with make_clickable', "This is a demo page with some the filter Make Cliakcble wich makes this link clickable: <a href='http://dbwebb.se'>a link to dbwebb.se</a>.", 'make_clickable', $this->user['id']));
          $this->AddMessage('success', 'Successfully created the database tables and created the default blog posts and pages owned by you.');
    	}
    	catch(Exception$e) {
    		die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
    	}
    }

    /**
  	* Save content. If it has a id, use it to update current entry or else insert new entry.
 	* @return boolean, true if success else false.
    */
    public function Save() {
    	  $msg = null;
    	  if($this['id']) {
          $this->db->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['filter'], $this['title'], $this['data'], $this['id']));
          $msg = 'updated';
          } else {
          $this->db->ExecuteQuery(self::SQL('insert content'), array($this['key'], $this['type'], $this['filter'], $this['title'], $this['data'], $this->user['id']));
          $this['id'] = $this->db->LastInsertId();
          $msg = 'created';
          }
    	$rowcount = $this->db->RowCount();
    	if($rowcount) {
    		$this->AddMessage('success', "Successfully {$msg} content '{$this['key']}'.");
    	}
    	else {
    		$this->AddMessage('error', "Failed to {$msg} content '{$this['key']}'.");
    	}
    	return $rowcount === 1;
    }

    /**
    * Load content by its id
    * @param id integer, the id of the content 
    * @return boolean, true if success else false 
    */
    public function LoadById($id) {
    	$res = $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
	    if(empty($res)) {
	      $this->AddMessage('error', "Failed to load content with id '$id'.");
	      return false;
	    } else {
	      $this->data = $res[0];
	    }
	    return true;
    } 

    /**
    * List all content.
    * @return array with listing or null if empty.
    */
    public function ListAll($args=null) {
	    try {
	      if(isset($args) && isset($args['type'])) {
	        return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select * by type', $args), array($args['type']));
	      } else {
	        return $this->db->ExecuteSelectQueryAndFetchAll(self::SQL('select *', $args));
	      }
	    } catch(Exception $e) {
	      echo $e;
	      return null;
	    }
    }

    /**
    * Filter content according to a specified filter
    * @param $data string, filter and format text according to its filter settings
    * @return string with filtered data
    */
    public static function Filter($data, $filter) {
    	$valid = array('htmlpurify','bbcode','plain','make_clickable','markdownextra', 'smartypants');
	    if(in_array($filter, $valid)) {
	      $data = CTextFilter::filter($data, $filter);
	    } 
	    else {
	      $data = CTextFilter::filter($data, "plain");
		}
		return $data;
    }

    public function GetFilteredData() {
    	return $this->Filter($this['data'], $this['filter']);
    }


}